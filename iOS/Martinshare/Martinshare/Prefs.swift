//
//  Prefs.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 25.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import UIKit

class Prefs {
    
    static func getUsername()-> String {
        
        if let temp = UserDefaults.standard.string(forKey: "username") {
            return temp
        } else {
            return ""
        }
        
    }
    
    static func putUsername(_ username: String) {
    
        UserDefaults.standard.set(username, forKey: "username")
    }
    
    
    static func getGoodUsername()-> String {
        
        if let temp = UserDefaults.standard.string(forKey: "goodusername") {
            return temp
        } else {
            return ""
        }
        
    }
    
    static func putGoodUsername(_ goodusername: String) {
        
        UserDefaults.standard.set(goodusername, forKey: "goodusername")
    }
    
    static func getPassword()-> String {
        if let temp = UserDefaults.standard.string(forKey: "password") {
            return temp
        } else {
            return ""
        }
    }
    
    static func putPassword(_ password: String) {
        UserDefaults.standard.set(password, forKey: "password")
    }
    
    static func getKey()-> String {
        if let temp = UserDefaults.standard.string(forKey: "key") {
            return temp
        } else {
            return ""
        }
    }
    
    static func putKey(_ key: String) {
        
        UserDefaults.standard.set(key, forKey: "key")
    }
    
    static func getVertretungsplanURL()-> Array<String> {
        
        if let temp: Array<String> = UserDefaults.standard.object(forKey: "vertretungsplanurls") as? Array<String> {
            return temp
        } else {
            return Array<String>()
        }
        
    }
    
    static func putVertretungsplanURL(_ urls: Array<String>) {
        
        UserDefaults.standard.set(urls, forKey: "vertretungsplanurls")
    }

    
    static func getVertretungsplanMarkierung()-> String {
        if let temp: String =
            UserDefaults.standard.string(forKey: "vertretungsplanmarkierung") {
            return temp
        } else {
            return "Klasse"
        }
    }
    
    static func putVertretungsplanMarkierung(_ urls: String) {
        UserDefaults.standard.set(urls, forKey: "vertretungsplanmarkierung")
    }
    
    static func getVertretungsplanMarkierungFarbe()-> String {
        if let temp: String = UserDefaults.standard.string(forKey: "vertretungsplanmarkierungfarbe") {
                return temp
        } else {
            return "#ff0000"
        }
    }
    
    static func putVertretungsplanMarkierungFarbe(_ texthex: String) {
        UserDefaults.standard.set("#\(texthex)", forKey: "vertretungsplanmarkierungfarbe")
    }
    
    static var standartMarkierungsSize: Int = 30
    static var markierungsSizeUpperLimit: Int = 70
    static var markierungsSizeLowerLimit: Int = 10

    static func getVertretungsplanMarkierungSize()-> String {
        if let temp: String = UserDefaults.standard.string(forKey: "vertretungsplanmarkierungsize") {
            return temp
        } else {
            return "\(self.standartMarkierungsSize)"
        }
    }
    
    static func putVertretungsplanMarkierungSize(_ texthex: String) {
        UserDefaults.standard.set(texthex, forKey: "vertretungsplanmarkierungsize")
    }
    
    
    
    static func getEintraegeLastChanged()-> String {
        if let temp: String = UserDefaults.standard.string(forKey: "eintraegelastchanged") {
            return temp
        } else {
            return "0000"
        }
    }
    
    static func putEintraegeLastChanged(_ lastchanged: String) {
        UserDefaults.standard.set(lastchanged, forKey: "eintraegelastchanged")
    }
    
    fileprivate static var EINTRAEGE: Array<EintragDataContainer>!
    
    static func eintraege()-> Array<EintragDataContainer> {
        if(self.EINTRAEGE == nil) {
            self.EINTRAEGE = getEintraegeArray()
        }
        return EINTRAEGE
    }
    
    //Array um die Kalender Daten
    static func eintraegeDate()-> Array<String> {
        var arr: Array<String> = Array<String>()
        
        for eintrag in Prefs.eintraege() {
           // if(find(arr, eintrag.datum) == 0) {
                arr.append(eintrag.datum)
           // }
        }
        
        return arr
    }
    
    
    static func getEintraegeArray()-> Array<EintragDataContainer> {
        if let temp: NSArray = UserDefaults.standard.array(forKey: "eintraegearray") as NSArray? {
            
            
            var eintraege: Array<EintragDataContainer> = []
            
            for eintrag in temp {
                eintraege.append(EintragDataContainer(nData: eintrag as! NSDictionary))
            }
            
            
            eintraege.sort(by: { (this: EintragDataContainer, that: EintragDataContainer) -> Bool in
                return EintragDataContainer.sortByDatum(this.datum, that: that.datum)
            })
            
            return eintraege
            
        } else {
            return Array<EintragDataContainer>()
        }
    }
    
    static func getEintraegeNSArray()-> NSArray {
        if let temp: NSArray = UserDefaults.standard.array(forKey: "eintraegearray") as NSArray? {
            return temp
        } else {
            return NSArray()
        }
    }
    
    static func putEintraegeArray(_ eintraegeArray: NSArray) {
        
        var eintraege: Array<EintragDataContainer> = []

        for eintrag in eintraegeArray {
            eintraege.append(EintragDataContainer(nData: eintrag as! NSDictionary))
        }
        

        
        eintraege.sort(by: {
            (this: EintragDataContainer, that: EintragDataContainer) -> Bool in
            return EintragDataContainer.sortByDatum(this.datum, that: that.datum)
        })

        self.EINTRAEGE = eintraege

        UserDefaults.standard.set(eintraegeArray, forKey: "eintraegearray")
    }
    
    static func parseEintraegeArray(_ eintraegeArray: NSArray) -> Array<EintragDataContainer> {
        
        var eintraege: Array<EintragDataContainer> = []
        
        for eintrag in eintraegeArray {
            eintraege.append(EintragDataContainer(nData: eintrag as! NSDictionary))
        }
        
        //eintraege.sortInPlace({
        //    (this: EintragDataContainer, that: EintragDataContainer) -> Bool in
        //    return EintragDataContainer.sortByDatum(this.datum, that: that.datum)
        //})
        
        return eintraege;
    }

    
    static func eintraegeLoeschen() {
        self.EINTRAEGE = Array<EintragDataContainer>()
        UserDefaults.standard.set(NSArray(), forKey: "eintraegearray")
        self.putEintraegeLastChanged("0000")
    }
    
    

    class func putNotifInfo(_ name: String, notifInfo: NotificationPrefContainer) {
        
        let documentsDirectory = FileManager().urls(for: (.documentDirectory), in: .userDomainMask).first!
        let archiveURL = documentsDirectory.appendingPathComponent(name)
        
        let success = NSKeyedArchiver.archiveRootObject(notifInfo, toFile: archiveURL.path)
        
        if  (success != true) {
            print("failed to save")
        }
    }
    
    class func getNotifInfo(_ name: String) -> NotificationPrefContainer {
        
        let documentsDirectory = FileManager().urls(for: (.documentDirectory), in: .userDomainMask).first!
        let archiveURL = documentsDirectory.appendingPathComponent(name)
        
        if let data = NSKeyedUnarchiver.unarchiveObject(withFile: archiveURL.path) as? NotificationPrefContainer {
            return data
        } else {
            return NotificationPrefContainer()
        }

    }
    
    

    static func getPUSHID()-> String {
        if let temp = UserDefaults.standard.string(forKey: "pushid") {
            return temp
        } else {
            return "0"
        }
    }
    
    static func putPUSHID(_ pushid: String) {
        UserDefaults.standard.set(pushid, forKey: "pushid")
    }
    
}
