//
//  MainScreenController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 25.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import UIKit

class MainScreenController: UITabBarController {

    override func viewDidLoad() {
        UIApplication.shared.statusBarStyle = .default
    //    let setting = UIUserNotificationSettings(forTypes: [.Badge, .Alert, .Sound], categories: nil)
    //    UIApplication.sharedApplication().registerUserNotificationSettings(setting)
        UIApplication.shared.registerForRemoteNotifications()
        
    }

}
