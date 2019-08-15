//
//  KalenderTerminology.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 05.02.16.
//  Copyright © 2016 ModestasV Studios. All rights reserved.
//

import Foundation

class KalenderTerminology {
    static func Tag(_ val: Int) -> String{
        return val > 1 ? "Tagen" : "Tag"
    }
    
    static func Sekunde(_ val: Int) -> String{
        return val > 1 ? "Sekunden" : "Sekunde"
    }
    
    static func Minute(_ val: Int) -> String{
        return val > 1 ? "Minuten" : "Minute"
    }
    
    static func Stunde(_ val: Int) -> String{
        return val > 1 ? "Stunden" : "Stunde"
    }
}
