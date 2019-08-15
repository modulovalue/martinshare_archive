//
//  NoficationScheduleContainer.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 06.02.16.
//  Copyright © 2016 ModestasV Studios. All rights reserved.
//

import Foundation
import UIKit

class NotificationScheduleContainer {
    
    var countA = 0
    var countH = 0
    var countS = 0

    func addToNSC(_ typ: String) -> NotificationScheduleContainer{
        if(typ == "a") {
            countA += 1
        } else if (typ == "h") {
            countH += 1
        } else if (typ == "s") {
            countS += 1
        }
        return self
    }

}

