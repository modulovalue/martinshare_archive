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
            var splitted = split(eintrag.datum) { $0 == "-"}
            if(yearDict[splitted[0].toInt()!] == nil || yearDict[splitted[0].toInt()!]! == false ){
                yearDict[splitted[0].toInt()!] = true
            }
            if(monthDict[splitted[1].toInt()!] == nil || monthDict[splitted[1].toInt()!] == false ){
                monthDict[splitted[1].toInt()!] = true
            }
            if(dayDict[splitted[2].toInt()!] == nil || dayDict[splitted[2].toInt()!] == false ){
                dayDict[splitted[2].toInt()!] = true
            }
        }
    }
    
    func reset(eintraege: Array<EintragDataContainer>) {
        yearDict = nil
        monthDict = nil
        dayDict = nil
        
        for eintrag in eintraege {
            var splitted = split(eintrag.datum) { $0 == "-"}
            if(yearDict[splitted[0].toInt()!] == nil || yearDict[splitted[0].toInt()!]! == false ){
                yearDict[splitted[0].toInt()!] = true
            }
            if(monthDict[splitted[1].toInt()!] == nil || monthDict[splitted[1].toInt()!] == false ){
                monthDict[splitted[1].toInt()!] = true
            }
            if(dayDict[splitted[2].toInt()!] == nil || dayDict[splitted[2].toInt()!] == false ){
                dayDict[splitted[2].toInt()!] = true
            }
        }
    }
    
    func check(date: NSDate)-> Bool {
    
        var yearChecked: Bool = false
        var monthChecked: Bool = false
        var dayChecked: Bool = false
        
        let flags: NSCalendarUnit = .CalendarUnitDay | .CalendarUnitMonth | .CalendarUnitYear
        let components = NSCalendar.currentCalendar().components(flags, fromDate: date)
        
        let myear = components.year
        let mmonth = components.month
        let mday = components.day
        
        for (day, bool) in dayDict {
            if(day == mday) {
                dayChecked = true
                break
            }
        }
        
        for (month, bool) in monthDict {
            if(month == mmonth) {
                monthChecked = true
                break
            }
        }
        
        for (year, bool) in yearDict {
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