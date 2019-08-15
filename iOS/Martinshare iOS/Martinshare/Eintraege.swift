//
//  Eintraege.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 29.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation

class Eintraege: NSObject {
    
    var eintraege: Array<EintragDataContainer> = []
    
    init(eintraege: Array<EintragDataContainer>) {
        self.eintraege = eintraege
    }

    required init(coder aDecoder: NSCoder) {
        self.eintraege = (aDecoder.decodeObjectForKey("eintraege") as! Array<EintragDataContainer>?)!
    }
    
    func encodeWithCode(aCoder: NSCoder) {
        aCoder.encodeObject(self.eintraege, forKey: "eintraege")
    }

}