//
//  EintragDataContainer.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 29.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation
import UIKit

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
    static var typAusgeschriebenAr: NSDictionary = ["h": "Hausaufgabe", "a": "Arbeitstermin", "s": "Sonstiges"]
    func getBeschreibung()-> String {
        return beschreibung.htmlToString
    }
    func getTitel()-> String {
        return titel.htmlToString
    }
    
    required convenience init(coder aDecoder: NSCoder) {
        
        self.init()
        self.id             = aDecoder.decodeObjectForKey("id") as! String?
        self.typ            = aDecoder.decodeObjectForKey("typ") as! String?
        self.titel          = aDecoder.decodeObjectForKey("titel") as! String?
        self.beschreibung   = aDecoder.decodeObjectForKey("beschreibung") as! String?
        self.datum          = aDecoder.decodeObjectForKey("datum") as! String?
        self.erstelldatum   = aDecoder.decodeObjectForKey("erstelldatum") as! String?
    }
    
    func encodeWithCode(aCoder: NSCoder) {
        aCoder.encodeObject(self.id,                 forKey: "id")
        aCoder.encodeObject(self.typ,                forKey: "typ")
        aCoder.encodeObject(self.titel,              forKey: "titel")
        aCoder.encodeObject(self.beschreibung,       forKey: "beschreibung")
        aCoder.encodeObject(self.datum,              forKey: "datum")
        aCoder.encodeObject(self.erstelldatum,       forKey: "erstelldatum")
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
    }
    
    func cpy()-> EintragDataContainer {
        var eintrag = EintragDataContainer()
        eintrag.typ = self.typ
        eintrag.id = self.id
        eintrag.datum = self.datum
        eintrag.erstelldatum = self.erstelldatum
        eintrag.beschreibung = self.beschreibung
        eintrag.titel = self.titel
        return eintrag
    }
    
    //Returns true wenn gleich
    func compare(nEintrag: EintragDataContainer)-> Bool {
        return (nEintrag.typ == self.typ &&
            nEintrag.beschreibung == self.beschreibung &&
            nEintrag.id == self.id &&
            nEintrag.datum == self.datum &&
            nEintrag.erstelldatum == self.erstelldatum &&
        nEintrag.titel == self.titel)
    }
    
    func typAusgeschrieben()-> String {
        return EintragDataContainer.typAusgeschriebenAr[typ] as! String
    }
    
    static func getImage(typ: String)-> UIImage {
        return UIImage(named: "iconhpad")!
    }
    
    
    static func sortByDatum(this: String, that: String) -> Bool {
        //ändern für asc oder desc
        var fall: Bool = true
        
        var thisspl = split(this) { $0 == "-"}
        var thatspl = split(that) { $0 == "-"}
        
        if thisspl[0].toInt() == thatspl[0].toInt() {
            if thisspl[1].toInt() == thatspl[1].toInt() {
                if thisspl[2].toInt() == thatspl[2].toInt() {
                    fall = true
                } else if thisspl[2].toInt() > thatspl[2].toInt() {
                    fall = true
                } else {
                    fall = false
                }
            } else if thisspl[1].toInt() > thatspl[1].toInt()  {
                fall = true
            } else {
                fall = false
            }
        } else if thisspl[0].toInt() > thatspl[0].toInt(){
            fall = true
        } else {
            fall = false
        }
        
        return fall
    }
    
    static func getNextTyp(nowTyp: String)-> String {
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
}
