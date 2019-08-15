//
//  HeaderCell.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 28.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import UIKit
import Foundation

class HeaderCell: UITableViewCell {
    
    //var monthHeaderProtocol: MonthHeaderProtocol

    @IBAction func backBtn(sender: AnyObject) {
        println("PREV MONTH")
    }
    
    @IBOutlet weak var backBtnLbl: UIButton!
    
    @IBAction func nextBtn(sender: AnyObject) {
        println("NEXT MONTH")
    }
    
    @IBOutlet weak var nextBtnLbl: UIButton!
    
    @IBOutlet weak var monthLbl: UILabel!
    @IBOutlet weak var yearLbl: UILabel!
    
    
    
}
