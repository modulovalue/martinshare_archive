//
//  MartinshareAPI.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 25.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation
import UIKit
import Parse

class MartinshareAPI {
    
    static let LOGIN_URL             = "https://www.martinshare.com/api/api.php/login/"
    static let LOGOUT_URL            = "https://www.martinshare.com/api/api.php/logout/"
    static let ISLOGGEDIN_URL        = "https://www.martinshare.com/api/api.php/isloggedin/"
    static let VERTRETUNGSPLAN_URL   = "https://www.martinshare.com/api/api.php/getvertretungsplan/"
    static let STUNDENPLAN_URL       = "https://www.martinshare.com/api/api.php/getstundenplan/"
    static let GETEINTRAEGE_URL      = "https://www.martinshare.com/api/api.php/geteintraege/"
    static let GETACTIVITY_URL       = "https://www.martinshare.com/api/api.php/getactivity/"
    static let GETSUGGESTIONS_URL    = "https://www.martinshare.com/api/api.php/getnamesuggestion/"
    static let GETVERSIONHISTORY_URL = "https://www.martinshare.com/api/api.php/getversionhistory/"
    static let DELETEEINTRAG_URL     = "https://www.martinshare.com/api/api.php/deleteeintrag/"
    static let SENDFEEDBACK_URL      = "https://www.martinshare.com/api/api.php/sendfeedback/"
    static let NEUEREINTRAG_URL      = "https://www.martinshare.com/api/api.php/neuereintrag/"
    static let UPDATEEINTRAG_URL     = "https://www.martinshare.com/api/api.php/updateeintrag/"
    static let CHECKPUSH_URL         = "https://www.martinshare.com/api/api.php/checkpush/"
    static let TEST_URL              = "https://www.martinshare.com/api/api.php/test/"
    
    
    static func loginUser(_ username: String, password: String, key: String, logIn: LoginProtocol) {
        
        logIn.startedLogingIn()
        
        Alamofire.request(.POST, self.LOGIN_URL, parameters: ["username": username, "password": password, "key": key, "device": "appleios", "pushid": "0"])
            .responseJSON { response in
               
                if(response.result.isSuccess) {
                    
                    switch "\(response.response!.statusCode)" {

                    case "200":
                        
                        Prefs.putPassword(password)
                        Prefs.putUsername(username)
                        
                        let json = JSON(response.result.value!)
                        
                        if let goodusername = json["username"].string {
                            Prefs.putGoodUsername(goodusername)
                        }
                        if let key = json["key"].string {
                            Prefs.putKey(key)
                        }
                        
                        
                            PFInstallation.current().addUniqueObject(Prefs.getGoodUsername(), forKey: "channels")
                            PFInstallation.current().addUniqueObject("loggedin", forKey: "channels")
                            PFInstallation.current().saveEventually()
                      
                        logIn.rightCredentials()
                        
                        
                    default:
                        logIn.unknownError("\(response.response!.statusCode)")
                    }
                    
                } else {
                    
                    if(response.response != nil) {
                        
                        if(response.response!.statusCode == 403) {
                            logIn.wrongCredentials()
                        } else {
                            print("\(response.response!.statusCode )")
                            print(response.result.error!.localizedDescription)
                            print(response.result.error!.localizedFailureReason)
                            logIn.unknownError("\(response.result.error!.localizedFailureReason!)")
                        }
                    } else {
                        logIn.noInternetConnection()
                    }
                }
        }
    }
    
    static func isloggedin(_ username: String, key: String,  ili: IsLoggedInProtocol) {
        ili.startedChecking()
        if(ili.emptyCredentials(username, key: key)) {
            ili.neverWasLoggedIn()
        } else {
            Alamofire.request(.POST, self.ISLOGGEDIN_URL, parameters: ["username": username, "key": key])
                .response { request, response, data, error in
                    
                    if(error == nil) {
                        if response!.statusCode == 403 {
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
    
    static func getStundenplan(_ username: String, key: String,  conPro: ConnectionProtocol, myImageView: UIImageView) {
        
        conPro.startedCon()
        Alamofire.request(.POST, self.STUNDENPLAN_URL, parameters: ["username": username, "key": key])
            .responseString { response in
                
                
                if(response.result.isSuccess) {
                    if let link = response.result.value {
                        
                        
                        let link2 = link.replacingOccurrences(of: "\n", with: "")
                        print("\nLINK \(link2)")
                        
                        //let url: NSURL = NSURL(string: link2)!
                        //let urlRequest = NSURLRequest(URL: url)
                        
                        Alamofire.request(.GET, link2, parameters: nil)
                            .response { (request, response, data, error) in
                                
                                if(error != nil) {
                                    print("DOWNLOADING IMAGE ERROR")
                                    
                                    conPro.unknownError("\(response!.statusCode) & \(error!.localizedDescription) & \(error!.localizedFailureReason)")
                                } else {
                                    let image = UIImage(data: data!)
                                    
                                    //print("PATH: \(StundenplanController.fileInDocumentsDirectory(MartinshareAPI.stundenplanName))")
                                    
                                    StundenplanController.saveImage(image!, path: StundenplanController.fileInDocumentsDirectory(MartinshareAPI.stundenplanName))
                                    
                                    myImageView.image = StundenplanController.loadImageFromPath(StundenplanController.fileInDocumentsDirectory(MartinshareAPI.stundenplanName))
                                    
                                    conPro.success()
                                }

                                
                        }
                        
                    } else {
                        conPro.conFailed()
                    }

                } else {
        
                    if(response.response != nil) {
                        if response.response!.statusCode == 403 {
                            conPro.wrongCredentials()
                        } else {
                            conPro.unknownError("\(response.response!.statusCode)")
                        }
                    } else {
                        conPro.noInternetConnection()
                    }
                
                }

        }
    }
    
    
    static func getVertretungsplan(_ username: String, key: String, conPro: ConnectionProtocol) {
        
        conPro.startedCon()
        
        Alamofire.request(.POST, self.VERTRETUNGSPLAN_URL, parameters: ["username": username, "key": key])
            .responseJSON { response in
                
                if(response.result.isSuccess) {
                    
                    switch "\(response.response!.statusCode)" {

                    case "200":
                        let seiten = response.response!.allHeaderFields["Seiten"] as! NSString
                        
                        var seitenzahl: Int = 0
                        
                        if let variable: String = seiten as String {
                            if let integer = Int(variable) {
                                seitenzahl = integer
                            }
                        }
                        
                        var plan = response.response!.allHeaderFields["Domain"] as! String
                        plan += response.response!.allHeaderFields["Folder"] as! String
                        plan += response.response!.allHeaderFields["Schule"] as! String
                        plan += "/"
                        
                                var urls: Array<String> = Array()
                        
                                for index in 2...seitenzahl+2 {
                                    if let datei = JSON(response.result.value!)["\(index)"].string {
                                        urls.append("\(plan)\(datei)")
                                    }
                                }
                        
                        Prefs.putVertretungsplanURL(urls)  
                        conPro.success()
                    default:
                        conPro.unknownError("\(response.response!.statusCode)")
                    }
                    
                } else {
                    
                    if(response.response != nil) {
                        if response.response!.statusCode == 403 {
                            conPro.wrongCredentials()
                        } else {
                            conPro.unknownError("\(response.response!.statusCode)")
                        }
                    } else {
                        conPro.noInternetConnection()
                    }
                    
                }

        }
        
    }
    
    static func test() {
        Alamofire.request(.POST, self.TEST_URL, parameters: ["test": "TEST"])
            .responseJSON { response in
                
                if(response.result.isSuccess) {
                    print("SUCCESS: \(response.response!.statusCode)")
                    print(response.result.value)
                } else {
                    
                    print("FAILURE: ")
                    if(response.response != nil) {
                        print("Statuscode: \(response.response!.statusCode)")
                    }
                    print(response.result.value)
                    print(response.result.error!.localizedDescription)
                    print(response.result.error!.localizedFailureReason)
                    
                }
                
        }
    }
    
    static func ausloggen(_ username: String, key: String, failed: (() -> Void)?, erfolg: (() -> Void)?) {
        
        Alamofire.request(.POST, self.LOGOUT_URL, parameters: ["username": username, "key": key])
            .response { request, response, data, error in
                
                if(error == nil) {
                
                   
                    
                    PFInstallation.current().remove(Prefs.getGoodUsername(), forKey: "channels")
                    PFInstallation.current().remove("loggedin", forKey: "channels")
                    PFInstallation.current().saveEventually()
                    
                    let stundenplanfilepath = StundenplanController.fileInDocumentsDirectory(MartinshareAPI.stundenplanName)
                    do {
                        try FileManager.default.removeItem(atPath: stundenplanfilepath)
                    } catch {
                        print(error)
                    }
                    
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
    
    
    
    
    static func getEintraege(_ username: String,  key: String, lastChanged: String, geteintraege: GetEintraegeProtocol, warn: Bool, afterAktualisiertOderAktuell: @escaping ()-> Void = {}) {
        
        geteintraege.startedGetting()
        Alamofire.request(.POST, self.GETEINTRAEGE_URL, parameters: ["username": username, "key": key, "lastchanged": lastChanged])
            .responseJSON { response in
                
                if(response.result.isSuccess) {
                    
                    switch "\(response.response!.statusCode)" {
                    case "403":
                        geteintraege.notLoggedIn()
                        
                    case "200":
                        
                        if (response.response!.allHeaderFields["Haschanged"] as! String == "1") {
                            
                            Prefs.putEintraegeLastChanged(response.response!.allHeaderFields["Letztesupdate"] as! String)
                            
                            let ar: NSArray = response.result.value as! NSArray
                            
                            Prefs.putEintraegeArray(ar)
                            
                            NSLog("\(response.result.value)")
                            
                            geteintraege.aktualisiert(warn, afterAktualisiertOderAktuell: afterAktualisiertOderAktuell)
                        } else {
                            geteintraege.notChanged(warn)
                        }
                        
                        
                    default:
                        geteintraege.unknownError(" Status: \(response.response!.statusCode) & \(response.request) & \(response.response)   & \(response)")
                    }
                    
                } else {
                    
                    if(response.response != nil) {
                        
                        switch "\(response.response!.statusCode)" {
                        case "403":
                            geteintraege.notLoggedIn()
                            
                        default:
                            print(response.result.error!.localizedDescription)
                            print(response.result.error!.localizedFailureReason)
                            geteintraege.unknownError(" Status: \(response.response!.statusCode) & \(response.request) & \(response.response)   & \(response)")
                        }
                    } else {
                        geteintraege.noInternet()
                    }
                    
                }
                
        }
    }

    static func getActivityArray(_ username: String,  key: String, getActivity: GetActivityProtocol, warn: Bool, afterAktualisiertOderAktuell: @escaping ()-> Void = {}) {
        
        getActivity.startedGetting()
        Alamofire.request(.POST, self.GETACTIVITY_URL, parameters: ["username": username, "key": key])
            .responseJSON { response in
                
                if(response.result.isSuccess) {
                    
                    switch "\(response.response!.statusCode)" {
                    case "403":
                        getActivity.notLoggedIn()
                        
                    case "200":
                        let ar: NSArray = response.result.value as! NSArray
                            
                            
                        getActivity.aktualisiert(ar, warn: warn, afterAktualisiertOderAktuell: afterAktualisiertOderAktuell)
                        
                    default:
                        getActivity.unknownError(" Status: \(response.response!.statusCode) & \(response.request) & \(response.response)   & \(response)")
                    }
                    
                } else {
                    if(response.response != nil) {
                        
                        switch "\(response.response!.statusCode)" {
                        
                        case "403":
                            getActivity.notLoggedIn()
                            
                        default:
                            print(response.result.error!.localizedDescription)
                            print(response.result.error!.localizedFailureReason)
                            getActivity.unknownError(" Status: \(response.response!.statusCode) & \(response.request) & \(response.response)   & \(response)")
                        }
                    } else {
                        getActivity.noInternet()
                    }
                    
                }
                
        }
    }

    static func getNameSuggestions(_ username: String, key: String, date: String, name: String, gotSuggestion: GetSuggestion, fachView: AutoCompleteTextField) {
        
        Alamofire.request(.POST, self.GETSUGGESTIONS_URL, parameters: ["username": username, "key": key, "date": date, "name": name])
            .responseJSON { response in
                
                if(response.result.isSuccess) {
                    
                    switch "\(response.response!.statusCode)" {
                    case "403":
                        break
                        //geteintraege.notLoggedIn()
                    case "200":
                        print(date)
                        
                        if(ar.count != 0) {
                            fachView.hidesWhenEmpty = false
                        }
                        
                        gotSuggestion.gotSuggestion(ar)
                        
                    default:
                        break
                    }
                    
                } else {
                    
                    if(response.response != nil) {
                        switch "\(response.response!.statusCode)" {
                        case "403":
                            break
                            //geteintraege.notLoggedIn()
                        default:
                            break
                            //geteintraege.unknownError(" Status: \(response.response!.statusCode) & \(response.request) & \(response.response)   & \(response)")
                        }
                    } else {
                        //geteintraege.noInternet()
                    }
                    
                }
        }
    }
    
    
    static func getVersionHistory(_ username: String,  key: String, id: String, getVersionHistoryProtocol: GetVersionHistoryProtocol) {
        
        getVersionHistoryProtocol.startedGetting()
        
        Alamofire.request(.POST, self.GETVERSIONHISTORY_URL, parameters: ["username": username, "key": key, "id": id])
            .responseJSON { response in
                
                if(response.result.isSuccess) {
                    
                    switch "\(response.response!.statusCode)" {
                    case "403":
                        getVersionHistoryProtocol.notLoggedIn()
                        
                    case "409":
                        let changed = response.response!.allHeaderFields["Reason"] as! String
                        getVersionHistoryProtocol.unknownError(changed)
                        
                    case "200":
                        
                        let ar: NSArray = response.result.value as! NSArray
                        getVersionHistoryProtocol.got(Prefs.parseEintraegeArray(ar))
                    
                    default:
                        getVersionHistoryProtocol.unknownError(" Status: \(response.response!.statusCode) & \(response.request) & \(response.response)   & \(response)")
                    }
                    
                } else {
                    
                    if(response.response != nil) {
                        
                        switch "\(response.response!.statusCode)" {
                            
                        case "403":
                            getVersionHistoryProtocol.notLoggedIn()
                            
                        case "409":
                            let changed = response.response!.allHeaderFields["Reason"] as! String
                            getVersionHistoryProtocol.unknownError(changed)
                        default:
                            getVersionHistoryProtocol.unknownError(" Status: \(response.response!.statusCode) & \(response.request) & \(response.response)   & \(response)")
                            
                        }
                    } else {
                        getVersionHistoryProtocol.noInternet()
                    }
                    
                }
                
        }
    }
    
    static func eintragUpdaten(_ username: String, key: String, newEintrag: EintragDataContainer, eintragen: EintragenProtocol) {
        
        eintragen.startedConnection()
        Alamofire.request(.POST, self.UPDATEEINTRAG_URL, parameters: ["username": username, "key": key, "id": newEintrag.id, "fach": newEintrag.titel,"typ": newEintrag.typ, "beschreibung": newEintrag.beschreibung, "datum": newEintrag.datum])
            .response { request, response, data, error in
                
                if(error == nil) {
                    switch "\(response!.statusCode)" {
                    case "403":
                        eintragen.notLoggedIn()
                    case "409":
                        let changed = response!.allHeaderFields["Reason"] as! String
                        eintragen.error(changed)
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
    
    static func eintragDelete(_ username: String, key: String, deleteEintrag: EintragDataContainer, eintragen: EintragenProtocol) {
        
        eintragen.startedConnection()
        Alamofire.request(.POST, self.DELETEEINTRAG_URL, parameters: ["username": username, "key": key, "id": deleteEintrag.id])
            .response { request, response, data, error in
                
                if(error == nil) {
                    switch "\(response!.statusCode)" {
                    case "403":
                        eintragen.notLoggedIn()
                    case "409":
                        let changed = response!.allHeaderFields["Reason"] as! String
                        eintragen.error(changed)
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
    
    static func eintragEintragen(_ username: String, key: String, typ: String, fach: String,beschreibung: String, datum: String, eintragen: EintragenProtocol) {
        
        eintragen.startedConnection()
        Alamofire.request(.POST, self.NEUEREINTRAG_URL, parameters: ["username": username, "key": key, "typ": typ, "fach": fach, "beschreibung": beschreibung, "datum": datum])
            .response { request, response, data, error in
                
                if(error == nil) {
                    switch "\(response!.statusCode)" {
                    case "403":
                        eintragen.notLoggedIn()
                    case "409":
                        let changed = response!.allHeaderFields["Reason"] as! String
                        eintragen.error(changed)
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
    
    
    static func checkpush(_ username: String, key: String, pushid: String, checkpushprotocol: CheckPushProtocol) {
        
        Alamofire.request(.POST, CHECKPUSH_URL, parameters: ["username": username, "key": key, "device": "appleios", "pushkey": pushid])
            .responseString { response in
                
                if(response.result.isSuccess) {
                    
                    switch "\(response.response!.statusCode)" {
                
                    case "200":
                        checkpushprotocol.success()
                        let changed = response.response!.allHeaderFields["Haspushid"] as! NSString
                        
                        if "0" == changed {
                            checkpushprotocol.success()
                        } else {
                            checkpushprotocol.alreadyRight()
                        }
                        
                    case "403":
                        checkpushprotocol.wrongCredentials()
                    default:
                        checkpushprotocol.unknownError()
                    }
                    
                } else {
                    
                    if(response.response != nil) {
                        
                        if(response.response!.statusCode == 403) {
                            checkpushprotocol.wrongCredentials()
                        } else {
                            print("\(response.response!.statusCode)")
                            print(response.result.error!.localizedDescription)
                            print(response.result.error!.localizedFailureReason)
                            checkpushprotocol                .unknownError()
                        }
                    } else {
                        checkpushprotocol.noInternetConnection()
                    }
                }
        }
    }

    static func sendFeedback(_ username: String, key: String, message: String, feedbackprotocol: FeedbackProtocol) {
        
        feedbackprotocol.startedConnection()
        
        Alamofire.request(.POST, self.SENDFEEDBACK_URL, parameters: ["username": username, "key": key, "message": message, "device": "ios"])
            .response { request, response, data, error in
                
                if(error == nil) {
                    switch "\(response!.statusCode)" {
                    case "403":
                        feedbackprotocol.notLoggedIn()
                    case "409":
                        let changed = response!.allHeaderFields["Reason"] as! String
                        feedbackprotocol.error(changed)
                    case "200":
                        feedbackprotocol.sent()
                    default:
                        feedbackprotocol.unknownError()
                    }
                } else {
                    print(error?.localizedDescription)
                    print(error?.localizedFailureReason)
                    feedbackprotocol.noInternetForSending()
                }
        }
    }

    
}
