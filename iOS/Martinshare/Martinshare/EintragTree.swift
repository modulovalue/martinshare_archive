//
//  EintragTree.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 29.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation

class EintragTree {

    static var eintragTree: EintragTree!
    
    var yearDict: [Int: Bool]! = [:]
    var monthDict: [Int: Bool]! = [:]
    var dayDict: [Int: Bool]! = [:]
    
    
    init(eintraege: Array<EintragDataContainer>) {
        for eintrag in eintraege {
            var splitted = eintrag.datum.components(separatedBy: "-")
            if(yearDict[Int(splitted[0])!] == nil || yearDict[Int(splitted[0])!]! == false ){
                yearDict[Int(splitted[0])!] = true
            }
            if(monthDict[Int(splitted[1])!] == nil || monthDict[Int(splitted[1])!] == false ){
                monthDict[Int(splitted[1])!] = true
            }
            if(dayDict[Int(splitted[2])!] == nil || dayDict[Int(splitted[2])!] == false ){
                dayDict[Int(splitted[2])!] = true
            }
        }
    }
    
    func reset(_ eintraege: Array<EintragDataContainer>) {
        yearDict = nil
        monthDict = nil
        dayDict = nil
        
        for eintrag in eintraege {
            var splitted = eintrag.datum.components(separatedBy: "-")
            if(yearDict[Int(splitted[0])!] == nil || yearDict[Int(splitted[0])!]! == false ){
                yearDict[Int(splitted[0])!] = true
            }
            if(monthDict[Int(splitted[1])!] == nil || monthDict[Int(splitted[1])!] == false ){
                monthDict[Int(splitted[1])!] = true
            }
            if(dayDict[Int(splitted[2])!] == nil || dayDict[Int(splitted[2])!] == false ){
                dayDict[Int(splitted[2])!] = true
            }
        }
    }
    
    func check(_ date: Date)-> Bool {
    
        var yearChecked: Bool = false
        var monthChecked: Bool = false
        var dayChecked: Bool = false
        
        let flags: NSCalendar.Unit = [ .day, .month, .year]
        let components = (Calendar.current as NSCalendar).components(flags, from: date)
        
        let myear = components.year
        let mmonth = components.month
        let mday = components.day
        
        for (day, _) in dayDict {
            if(day == mday) {
                dayChecked = true
                break
            }
        }
        
        for (month, _) in monthDict {
            if(month == mmonth) {
                monthChecked = true
                break
            }
        }
        
        for (year, _) in yearDict {
            if(year == myear) {
                yearChecked = true
                break
            }
        }
        
        if (yearChecked && monthChecked && dayChecked) {
            return true
        } else {
            return false
        }
    }
    
    
    static func getEintragTree() -> EintragTree {
        if(eintragTree == nil) {
            self.eintragTree = EintragTree(eintraege: Prefs.eintraege())
        }
        return self.eintragTree
    }
    
}
