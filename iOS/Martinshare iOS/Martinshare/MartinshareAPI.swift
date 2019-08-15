//
//  MartinshareAPI.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 25.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation
import UIKit

class MartinshareAPI {
    
    static let LOGIN_URL = "http://www.martinshare.com/api/api.php/login/"
    static let LOGOUT_URL = "http://www.martinshare.com/api/api.php/logout/"
    static let ISLOGGEDIN_URL = "http://www.martinshare.com/api/api.php/isloggedin/"
    static let VERTRETUNGSPLAN_URL = "http://www.martinshare.com/api/api.php/getvertretungsplan/"
    static let STUNDENPLAN_URL = "http://www.martinshare.com/api/api.php/getstundenplan/"
    static let GETEINTRAEGE_URL = "http://www.martinshare.com/api/api.php/geteintraege/"
    static let NEUEREINTRAG_URL = "http://www.martinshare.com/api/api.php/neuereintrag/"
    static let UPDATEEINTRAG_URL = "http://www.martinshare.com/api/api.php/updateeintrag/"
    
    
    
    static func loginUser(username: String, password: String, key: String, logIn: LoginProtocol) {
        
        logIn.startedLogingIn()
    
        Alamofire.manager.request(.POST, self.LOGIN_URL, parameters: ["username": username, "password": password, "key": key, "device": "appleios", "pushid": "0"])
            .responseJSON { (request, response, data, error) in
                
                
                if(error == nil) {
                    
                    var codestr: String = "NOPE"
                    
                    if (response?.statusCode != nil) {
                        codestr = "\(response!.statusCode)"
                    }
                    
                    println(codestr)
                    switch codestr {
                        case "403":
                            logIn.wrongCredentials()
                        case "200":
                            
                            Prefs.putPassword(password)
                            Prefs.putUsername(username)
                            
                            let json = JSON(data!)
                            
                            if let goodusername = json["username"].string {
                                Prefs.putGoodUsername(goodusername)
                            }
                            if let key = json["key"].string {
                                Prefs.putKey(key)
                            }
                        
                            
                            logIn.rightCredentials()
                        
                        default:
                            logIn.unknownError()
                    }
                    
                } else {
                    logIn.noInternetConnection()
                    

                }
                
            }
    }
    
    static func isloggedin(username: String, key: String,  ili: IsLoggedInProtocol) {
        ili.startedChecking()
        if(ili.emptyCredentials(username, key: key)) {
            ili.neverWasLoggedIn()
        } else {
            Alamofire.manager.request(.POST, self.ISLOGGEDIN_URL, parameters: ["username": username, "key": key])
                .responseJSON { (request, response, data, error) in
                    
                    if(error == nil) {
                        println("isloggedinerror \(response?.statusCode)")
                        if response?.statusCode == 403 {
                            ili.isNotLoggedIn()
                        } else {
                            ili.isLoggedIn()
                        }
                    } else {
                        ili.isLoggedIn()
                        print("ISLOGGEDIN ERROR UNGLEICH NIL", error)
                    }
                    
               }
        }
    }

    static var stundenplanName = "stundenplan"
    
    static func getStundenplan(username: String, key: String,  conPro: ConnectionProtocol, myImageView: UIImageView) {

        conPro.startedCon()
            Alamofire.manager.request(.POST, self.STUNDENPLAN_URL, parameters: ["username": username, "key": key])
                .responseString{ (request, response, data, error) in
                    
                    if(error == nil) {
                        if response?.statusCode == 403 {
                            conPro.wrongCredentials()
                            print("DATAAAAAAAAA")
                        } else {
                            
                            let json = JSON(data!)
                            
                            if let link = json.string {
                                
                                let url = NSURL(string: link)
                                let urlRequest = NSURLRequest(URL: url!)
                                
                                NSURLConnection.sendAsynchronousRequest(urlRequest, queue: NSOperationQueue.mainQueue(), completionHandler: {
                                    response, data, error in
                                    
                                    if(error != nil) {
                                        println("DOWNLOADING IMAGE ERROR")
                                        conPro.conFailed()
                                    } else {
                                        let image = UIImage(data: data!)
                                        StundenplanController.saveImage(image!, path: StundenplanController.fileInDocumentsDirectory(MartinshareAPI.stundenplanName))
                                        
                                        myImageView.image = StundenplanController.loadImageFromPath(StundenplanController.fileInDocumentsDirectory(MartinshareAPI.stundenplanName))
                                        
                                        conPro.success()
                                    }
                                })
                                
                            } else {
                                conPro.conFailed()
                            }
                            
                        }
                    } else {
                        conPro.noInternetConnection()
                        print("ERROR UNGLEICH NIL  \(error)")
                    }
                    
            }
    }

    
    static func getVertretungsplan(username: String, key: String, conPro: ConnectionProtocol) {
        
        conPro.startedCon()
        
        Alamofire.manager.request(.POST, self.VERTRETUNGSPLAN_URL, parameters: ["username": username, "key": key])
                .responseJSON { (request, response, data, error) in
                    
                    if(error == nil) {
                        if response?.statusCode == 403 {
                            //not logged in
                            conPro.wrongCredentials()
                            
                            print("NOT Logged in", error)
                        } else {
                            
                            println()
                            
                            var seiten = response?.allHeaderFields["Seiten"] as! NSString
                            
                            var seitenzahl: Int = 0
                            
                            if let variable: String = seiten as? String {
                                if let integer = variable.toInt() {
                                    seitenzahl = integer
                                }
                            }
                            
                            var plan = response?.allHeaderFields["Domain"] as! String
                            plan += response?.allHeaderFields["Folder"] as! String
                            plan += response?.allHeaderFields["Schule"] as! String
                            plan += "/"
                            
                            var urls: Array<String> = Array()
                            
                            for index in 2...seitenzahl+1 {
                                if let datei = JSON(data!)["\(index)"].string {
                                    urls.append(plan + datei)
                                }
                            }
                            Prefs.putVertretungsplanURL(urls)
                            
                            conPro.success()
                        }
                        
                    } else {
                        
                        conPro.noInternetConnection()
                        
                    }
                    
            }
        
    }
    
    static func ausloggen(username: String, key: String, failed: (() -> Void)?, erfolg: (() -> Void)?) {
        
        
        Alamofire.manager.request(.POST, self.LOGOUT_URL, parameters: ["username": username, "key": key])
            .responseJSON { (request, response, data, error) in
                
                if(error == nil) {
                    Prefs.putGoodUsername("")
                    Prefs.putKey("")
                    Prefs.putPassword("")
                    Prefs.putVertretungsplanMarkierung("Klasse")
                    Prefs.putUsername("")
                    Prefs.putVertretungsplanMarkierungFarbe("ff0000")
                    Prefs.putVertretungsplanURL(Array<String>())
                    Prefs.eintraegeLoeschen()
                    erfolg!()
                } else {
                    failed!()
                }
                
        }

    }

    
    static func getEintraege(username: String,  key: String, lastChanged: String, geteintraege: GetEintraegeProtocol, warn: Bool, afterAktualisiertOderAktuell: ()-> Void = {}) {
        
        geteintraege.startedGetting()
        Alamofire.manager.request(.POST, self.GETEINTRAEGE_URL, parameters: ["username": username, "key": key, "lastchanged": lastChanged])
                .responseJSON { (request, response, data, error) in
                
                    
                if(error == nil) {
                    
                    switch "\(response!.statusCode)" {
                    case "403":
                        geteintraege.notLoggedIn()
                        
                    case "200":
                        
                        if (response!.allHeaderFields["Haschanged"] as! String == "1") {
                        

                            Prefs.putEintraegeLastChanged(response!.allHeaderFields["Letztesupdate"] as! String)
                            
                            var ar: NSArray = data as! NSArray

                            Prefs.putEintraegeArray(ar)
                            
                            NSLog("\(data)")
                                
                            geteintraege.aktualisiert(warn, afterAktualisiertOderAktuell: afterAktualisiertOderAktuell)
                        } else {
                            geteintraege.notChanged(warn)
                        }
                        
                        
                    default:
                        geteintraege.unknownError()
                    }
                    
                } else {
                    print(error?.localizedDescription)
                    print(error?.localizedFailureReason)
                    geteintraege.noInternet()
                }
                
        }
    }
    
    static func eintragUpdaten(username: String, key: String, newEintrag: EintragDataContainer, eintragen: EintragenProtocol) {
        
        eintragen.startedConnection()
        Alamofire.manager.request(.POST, self.UPDATEEINTRAG_URL, parameters: ["username": username, "key": key, "id": newEintrag.id, "fach": newEintrag.getTitel(),"typ": newEintrag.typ, "beschreibung": newEintrag.getBeschreibung(), "datum": newEintrag.datum])
            .responseJSON { (request, response, data, error) in
                
                if(error == nil) {
                    switch "\(response!.statusCode)" {
                    case "403":
                        eintragen.notLoggedIn()
                        
                    case "200":
                        
                        eintragen.aktualisiert()
                        
                    default:
                        eintragen.unknownError()
                    }
                    
                } else {
                    print(error?.localizedDescription)
                    print(error?.localizedFailureReason)
                    eintragen.noInternet()
                }
        }
    }

    static func eintragEintragen(username: String, key: String, typ: String, fach: String,beschreibung: String, datum: String, eintragen: EintragenProtocol) {
        
        eintragen.startedConnection()
        Alamofire.manager.request(.POST, self.NEUEREINTRAG_URL, parameters: ["username": username, "key": key, "typ": typ, "fach": fach, "beschreibung": beschreibung, "datum": datum])
            .responseJSON { (request, response, data, error) in
                
                if(error == nil) {
                    switch "\(response!.statusCode)" {
                    case "403":
                        eintragen.notLoggedIn()
                        
                    case "200":
                        
                        eintragen.aktualisiert()
                        
                    default:
                        eintragen.unknownError()
                    }
                    
                } else {
                    print(error?.localizedDescription)
                    print(error?.localizedFailureReason)
                    eintragen.noInternet()
                }
        }
    }


    
}
