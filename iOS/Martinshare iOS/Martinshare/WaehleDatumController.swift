//
//  WaehleDatumController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 31.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation
import UIKit
class WaehleDatumController: UIViewController, RSDFDatePickerViewDelegate, RSDFDatePickerViewDataSource {

    @IBOutlet weak var calendar: RSDFDatePickerView!
    
    var getDate: GetDateProtocol!
    
    var altesDatum: String!
    
    @IBAction func abbrechenBtn(sender: AnyObject) {
        navigationController?.dismissViewControllerAnimated(true, completion: nil)
    }
    
    override func viewDidLoad() {
        calendar.delegate = self
        calendar.dataSource = self
        calendar.selectDate(NSDate().dateFromString(altesDatum, format: "yyyy-MM-dd"))
    }
    
    func datePickerView(view: RSDFDatePickerView!, didSelectDate date: NSDate!) {
        getDate.putDate(NSDate.getStringDatum(date))
        navigationController?.dismissViewControllerAnimated(true, completion: nil)
    }

}