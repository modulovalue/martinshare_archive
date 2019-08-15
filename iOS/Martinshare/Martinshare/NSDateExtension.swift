//
//  NSDateExtension.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 28.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation

extension Date {

    func dateFromString(_ date: String, format: String) -> Date {
        
        let formatter = DateFormatter()
        let locale = Locale(identifier: "en_US_POSIX")
        
        formatter.locale = locale
        formatter.dateFormat = format
        
        return formatter.date(from: date)!
    }
    
    static func germanDateFromString(_ date: Date) -> String {
        

        let flags: NSCalendar.Unit = [.day, .month, .year]
        let components = (Calendar.current as NSCalendar).components(flags, from: date)
        
        let year = components.year
        let month = components.month
        let day = components.day
        
        var deDatum = "\(Date.getLeadingZeroString(day!))"
        deDatum += "."
        deDatum += "\(Date.getLeadingZeroString(month!))"
        deDatum += "."
        deDatum += "\(year)"
        
        return deDatum
    }
    
    static func getStringDatum(_ date: Date)-> String {
        
        let flags: NSCalendar.Unit = [.day, .month, .year]
        let components = (Calendar.current as NSCalendar).components(flags, from: date)
        
        let year = components.year
        let month = components.month
        let day = components.day
        
        
        return "\(year)-\(Date.getLeadingZeroString(month!))-\(Date.getLeadingZeroString(day!))"
        
    }
    
    static func getDeStringDatumFromString(_ date: String)-> String {
        let etwas = date.components(separatedBy: "-")
        return "\(etwas[2]).\(etwas[1]).\(etwas[0])"
    }
    
    static func getLeadingZeroString(_ zahl: Int) -> String {
        let leadingZeroFormatter: NumberFormatter = NumberFormatter()
        leadingZeroFormatter.minimumIntegerDigits = 2
        return leadingZeroFormatter.string(from: NSNumber(value: zahl))!
    }
    
    static func eintraegeArrayFromDate(_ date: Date, eintraege: Array<EintragDataContainer>) ->Array<EintragDataContainer> {
        
        var dayEintraege: Array<EintragDataContainer> = Array<EintragDataContainer>()
        
        for eintrag in eintraege {
            if(eintrag.datum == Date.getStringDatum(date)) {
                dayEintraege.append(eintrag)
            }
        }

        return dayEintraege
    }
}
