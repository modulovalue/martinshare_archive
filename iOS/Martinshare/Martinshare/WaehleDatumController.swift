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
    
    @IBAction func abbrechenBtn(_ sender: AnyObject) {
        navigationController?.dismiss(animated: true, completion: nil)
    }
    
    override func viewDidLoad() {
        calendar.delegate = self
        calendar.dataSource = self
        calendar.select(Date().dateFromString(altesDatum, format: "yyyy-MM-dd"))
    }
    
    func datePickerView(_ view: RSDFDatePickerView!, didSelect date: Date!) {
        getDate.putDate(Date.getStringDatum(date))
        navigationController?.dismiss(animated: true, completion: nil)
    }

}
