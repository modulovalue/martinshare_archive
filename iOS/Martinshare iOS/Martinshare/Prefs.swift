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
        
        if let temp = NSUserDefaults.standardUserDefaults().stringForKey("username") {
            return temp
        } else {
            return ""
        }
        
    }
    
    static func putUsername(username: String) {
    
        NSUserDefaults.standardUserDefaults().setObject(username, forKey: "username")
    }
    
    
    static func getGoodUsername()-> String {
        
        if let temp = NSUserDefaults.standardUserDefaults().stringForKey("goodusername") {
            return temp
        } else {
            return ""
        }
        
    }
    
    static func putGoodUsername(goodusername: String) {
        
        NSUserDefaults.standardUserDefaults().setObject(goodusername, forKey: "goodusername")
    }
    
    static func getPassword()-> String {
        if let temp = NSUserDefaults.standardUserDefaults().stringForKey("password") {
            return temp
        } else {
            return ""
        }
    }
    
    static func putPassword(password: String) {
        NSUserDefaults.standardUserDefaults().setObject(password, forKey: "password")
    }
    
    static func getKey()-> String {
        if let temp = NSUserDefaults.standardUserDefaults().stringForKey("key") {
            return temp
        } else {
            return ""
        }
    }
    
    static func putKey(key: String) {
        
        NSUserDefaults.standardUserDefaults().setObject(key, forKey: "key")
    }
    
    static func getVertretungsplanURL()-> Array<String> {
        
        if let temp: Array<String> = NSUserDefaults.standardUserDefaults().objectForKey("vertretungsplanurls") as? Array<String> {
            return temp
        } else {
            return Array<String>()
        }
        
    }
    
    static func putVertretungsplanURL(urls: Array<String>) {
        
        NSUserDefaults.standardUserDefaults().setObject(urls, forKey: "vertretungsplanurls")
    }

    
    static func getVertretungsplanMarkierung()-> String {
        if let temp: String =
            NSUserDefaults.standardUserDefaults().stringForKey("vertretungsplanmarkierung") {
            return temp
        } else {
            return "Klasse"
        }
    }
    
    static func putVertretungsplanMarkierung(urls: String) {
        NSUserDefaults.standardUserDefaults().setObject(urls, forKey: "vertretungsplanmarkierung")
    }
    
    static func getVertretungsplanMarkierungFarbe()-> String {
        if let temp: String = NSUserDefaults.standardUserDefaults().stringForKey("vertretungsplanmarkierungfarbe") {
                return temp
        } else {
            return "#ff0000"
        }
    }
    
    static func putVertretungsplanMarkierungFarbe(texthex: String) {
        NSUserDefaults.standardUserDefaults().setObject("#\(texthex)", forKey: "vertretungsplanmarkierungfarbe")
    }
    
    static var standartMarkierungsSize: Int = 30
    static var markierungsSizeUpperLimit: Int = 70
    static var markierungsSizeLowerLimit: Int = 10

    static func getVertretungsplanMarkierungSize()-> String {
        if let temp: String = NSUserDefaults.standardUserDefaults().stringForKey("vertretungsplanmarkierungsize") {
            return temp
        } else {
            return "\(self.standartMarkierungsSize)"
        }
    }
    
    static func putVertretungsplanMarkierungSize(texthex: String) {
        NSUserDefaults.standardUserDefaults().setObject(texthex, forKey: "vertretungsplanmarkierungsize")
    }
    
    
    
    static func getEintraegeLastChanged()-> String {
        if let temp: String = NSUserDefaults.standardUserDefaults().stringForKey("eintraegelastchanged") {
            return temp
        } else {
            return "0000"
        }
    }
    
    static func putEintraegeLastChanged(lastchanged: String) {
        NSUserDefaults.standardUserDefaults().setObject(lastchanged, forKey: "eintraegelastchanged")
    }
    
    private static var EINTRAEGE: Array<EintragDataContainer>!
    
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
        if let temp: NSArray = NSUserDefaults.standardUserDefaults().arrayForKey("eintraegearray") {
            
            
            var eintraege: Array<EintragDataContainer> = []
        
            for eintrag in temp {
                eintraege.append(EintragDataContainer(nData: eintrag as! NSDictionary))
            }
            
            var ordered: Array<EintragDataContainer> = sorted(eintraege,  {
                (this: EintragDataContainer, that: EintragDataContainer) -> Bool in
                return EintragDataContainer.sortByDatum(this.datum, that: that.datum)
            })
        
            return ordered
            
        } else {
            return Array<EintragDataContainer>()
        }
    }
    
    static func putEintraegeArray(eintraegeArray: NSArray) {
        
        var eintraege: Array<EintragDataContainer> = []
        
        for eintrag in eintraegeArray {
            eintraege.append(EintragDataContainer(nData: eintrag as! NSDictionary))
        }
        
        let ordered: Array<EintragDataContainer> = sorted(eintraege,  {
            (this: EintragDataContainer, that: EintragDataContainer) -> Bool in
            return EintragDataContainer.sortByDatum(this.datum, that: that.datum)
        })

        self.EINTRAEGE = ordered

        NSUserDefaults.standardUserDefaults().setObject(eintraegeArray, forKey: "eintraegearray")
    }

    
    static func eintraegeLoeschen() {
        
        self.EINTRAEGE = Array<EintragDataContainer>()
        NSUserDefaults.standardUserDefaults().setObject(NSArray(), forKey: "eintraegearray")
        self.putEintraegeLastChanged("0000")
    }
}
