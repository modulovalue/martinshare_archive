//
//  PlanDisplayController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 05.02.16.
//  Copyright © 2016 ModestasV Studios. All rights reserved.
//

import Foundation
import UIKit

class PlanDisplayController: UITableViewController {
    
    override func viewDidLoad() {
        navigationController?.navigationBar.barTintColor = UIColor.white
        navigationController?.navigationBar.isTranslucent = false
        navigationController?.tabBarController?.tabBar.isTranslucent = false
        
    }
    
    override func viewDidAppear(_ animated: Bool) {
        if revealViewController() != nil {
            // revealViewController().rearViewRevealWidth = 250
            view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }

    }
    
}
