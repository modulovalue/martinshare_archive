//
//  EintragDataContainer.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 29.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation
import UIKit
fileprivate func < <T : Comparable>(lhs: T?, rhs: T?) -> Bool {
  switch (lhs, rhs) {
  case let (l?, r?):
    return l < r
  case (nil, _?):
    return true
  default:
    return false
  }
}

fileprivate func > <T : Comparable>(lhs: T?, rhs: T?) -> Bool {
  switch (lhs, rhs) {
  case let (l?, r?):
    return l > r
  default:
    return rhs < lhs
  }
}


class EintragDataContainer: NSObject {

    //eintrag id                            eg: 23
    var id: String!
    
    //typ a = arbeitstermin oder s = sonstiges oder h = hausaufgaben
    var typ: String!
    
    //fach des eintrags                     eg: Deutsch
    var titel: String!
    
    // inhalt des eintags                   eg: blabla
    var beschreibung: String!
    
    //bis wann muss es erledigt werden      eg: 2014-12-10 // 2014-11-07 // 2014-11-19
    var datum: String!
    
    //eintrags erstelldatum                 eg: 2014-11-17 08:21:50 // 2014-11-17 14:46:56 // 2014-11-19 11:55:30
    var erstelldatum: String!
    
    //deleted
    var deleted: String!
    
    //eintragsversion, 1 = new, >1 -> updates verfügbar
    var version: String!
    
    static var typAusgeschriebenAr: NSDictionary = ["h": "Hausaufgabe", "a": "Arbeitstermin", "s": "Sonstiges", "f": "Ferien"]
    
    func getBeschreibung()-> String {
        return beschreibung.htmlToString
    }
    
    func getTitel()-> String {
        return titel.htmlToString
    }
    
    required convenience init(coder aDecoder: NSCoder) {
        
        self.init()
        self.id             = aDecoder.decodeObject(forKey: "id") as! String?
        self.typ            = aDecoder.decodeObject(forKey: "typ") as! String?
        self.titel          = aDecoder.decodeObject(forKey: "titel") as! String?
        self.beschreibung   = aDecoder.decodeObject(forKey: "beschreibung") as! String?
        self.datum          = aDecoder.decodeObject(forKey: "datum") as! String?
        self.erstelldatum   = aDecoder.decodeObject(forKey: "erstelldatum") as! String?
        self.deleted        = aDecoder.decodeObject(forKey: "deleted") as! String?
        self.version        = aDecoder.decodeObject(forKey: "version") as! String?
    }
    
    func encodeWithCode(_ aCoder: NSCoder) {
        aCoder.encode(self.id,                 forKey: "id")
        aCoder.encode(self.typ,                forKey: "typ")
        aCoder.encode(self.titel,              forKey: "titel")
        aCoder.encode(self.beschreibung,       forKey: "beschreibung")
        aCoder.encode(self.datum,              forKey: "datum")
        aCoder.encode(self.erstelldatum,       forKey: "erstelldatum")
        aCoder.encode(self.deleted,            forKey: "deleted")
        aCoder.encode(self.version,            forKey: "version")
    }
    
    override init() {
        super.init()
    }

    init(nData: NSDictionary) {
        super.init()
        if let a = nData["id"] as? String {
            self.id = a
        }
        
        if let a = nData["typ"] as? String {
            self.typ = a
        }
        
        if let a = nData["name"] as? String {
            self.titel = a
        }
        
        if let a = nData["beschreibung"] as? String {
            self.beschreibung = a
        }
        
        if let a = nData["datum"] as? String {
            self.datum = a
        }
        
        if let a = nData["erstelldatum"] as? String {
            self.erstelldatum = a
        }
        
        if let a = nData["deleted"] as? String {
            self.deleted = a
        }
        
        if let a = nData["version"] as? String {
            self.version = a
        }
    }
    
    func cpy()-> EintragDataContainer {
        let eintrag = EintragDataContainer()
        eintrag.typ = self.typ
        eintrag.id = self.id
        eintrag.datum = self.datum
        eintrag.erstelldatum = self.erstelldatum
        eintrag.beschreibung = self.beschreibung
        eintrag.titel = self.titel
        eintrag.deleted = self.deleted
        eintrag.version = self.version
        return eintrag
    }
    
    //Returns true wenn gleich
    func compare(_ nEintrag: EintragDataContainer)-> Bool {
        return (nEintrag.typ == self.typ &&
            nEintrag.beschreibung == self.beschreibung &&
            nEintrag.id == self.id &&
            nEintrag.datum == self.datum &&
            nEintrag.erstelldatum == self.erstelldatum &&
            nEintrag.titel == self.titel &&
            nEintrag.deleted == self.deleted &&
            nEintrag.version == self.version)
    }
    
    func typAusgeschrieben()-> String {
        return EintragDataContainer.typAusgeschriebenAr[typ] as! String
    }
    
    static func getImage(_ typ: String)-> UIImage {
        return UIImage(named: "iconhpad")!
    }
    
    func firstVersion()-> Bool {
        if(version == "1") {
            return true;
        } else {
            return false;
        }
    }
    
    static func sortByDatum(_ this: String, that: String) -> Bool {
        //ändern für asc oder desc
        var fall: Bool = true
        
        var thisspl = this.components(separatedBy: "-")
        var thatspl = that.components(separatedBy: "-")
        
        if Int(thisspl[0]) == Int(thatspl[0]) {
            if Int(thisspl[1]) == Int(thatspl[1]) {
                if Int(thisspl[2]) == Int(thatspl[2]) {
                    fall = true
                } else if Int(thisspl[2]) > Int(thatspl[2]) {
                    fall = true
                } else {
                    fall = false
                }
            } else if Int(thisspl[1]) > Int(thatspl[1])  {
                fall = true
            } else {
                fall = false
            }
        } else if Int(thisspl[0]) > Int(thatspl[0]){
            fall = true
        } else {
            fall = false
        }
        
        return fall
    }
    
    static func getNextTyp(_ nowTyp: String)-> String {
        switch nowTyp {
        case "h":
            return "a"
        case "a":
            return "s"
        case "s":
            return "h"
        default:
            return "h"
        }
    }
    
    static func getEintragStringSingular(_ singular: Bool) ->  String {
        return singular ? "gelöschter Eintrag" : "gelöschte Einträge"
    }
}
