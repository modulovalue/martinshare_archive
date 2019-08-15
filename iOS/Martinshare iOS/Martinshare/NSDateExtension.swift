//
//  NSDateExtension.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 28.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation

extension NSDate {

    func dateFromString(date: String, format: String) -> NSDate {
        
        let formatter = NSDateFormatter()
        let locale = NSLocale(localeIdentifier: "en_US_POSIX")
        
        formatter.locale = locale
        formatter.dateFormat = format
        
        return formatter.dateFromString(date)!
    }
    
    static func germanDateFromString(date: NSDate) -> String {
        
        
        let flags: NSCalendarUnit = .CalendarUnitDay | .CalendarUnitMonth | .CalendarUnitYear
        let components = NSCalendar.currentCalendar().components(flags, fromDate: date)
        
        let year = components.year
        let month = components.month
        let day = components.day
        
        var deDatum = "\(NSDate.getLeadingZeroString(day))"
        deDatum += "."
        deDatum += "\(NSDate.getLeadingZeroString(month))"
        deDatum += "."
        deDatum += "\(year)"
        
        return deDatum
    }
    
    static func getStringDatum(date: NSDate)-> String {
        
        let flags: NSCalendarUnit = .CalendarUnitDay | .CalendarUnitMonth | .CalendarUnitYear
        let components = NSCalendar.currentCalendar().components(flags, fromDate: date)
        
        let year = components.year
        let month = components.month
        let day = components.day
        
        
        return "\(year)-\(NSDate.getLeadingZeroString(month))-\(NSDate.getLeadingZeroString(day))"
        
    }
    
    static func getDeStringDatumFromString(date: String)-> String {
        let etwas = split(date) {$0 == "-"}
        return "\(etwas[2]).\(etwas[1]).\(etwas[0])"
    }
    
    static func getLeadingZeroString(zahl: Int) -> String {
        var leadingZeroFormatter: NSNumberFormatter = NSNumberFormatter()
        leadingZeroFormatter.minimumIntegerDigits = 2
        return leadingZeroFormatter.stringFromNumber(zahl)!
        
    }
    
    static func eintraegeArrayFromDate(date: NSDate, eintraege: Array<EintragDataContainer>) ->Array<EintragDataContainer> {
        
        var dayEintraege: Array<EintragDataContainer> = Array<EintragDataContainer>()
        
        for eintrag in eintraege {
            if(eintrag.datum == NSDate.getStringDatum(date)) {
                dayEintraege.append(eintrag)
            }
        }

        return dayEintraege
    }
}