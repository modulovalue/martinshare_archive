//
//  Permissions.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 07.02.16.
//  Copyright © 2016 ModestasV Studios. All rights reserved.
//

import Foundation

class MSPermissions {
    
    let multiPscope = PermissionScope()
    
    init() {
        multiPscope.headerLabel.text = "Hey du! 👻"
        multiPscope.bodyLabel.text = "Hi! Wir brauchen ein paar Dinge von dir."
    }
    
    func showPermissions(_ askWhenDisabledOrAuthorized: Bool) {
        
        print(PermissionScope().statusNotifications())
        
        if(askWhenDisabledOrAuthorized) {
            switch PermissionScope().statusNotifications() {
            case .unknown, .disabled:
                showPermissionAsk2()
            case .unauthorized:
                if let appSettings = URL(string: UIApplicationOpenSettingsURLString) {
                    UIApplication.shared.openURL(appSettings)
                }
                return
            case .authorized:
                if let appSettings = URL(string: UIApplicationOpenSettingsURLString) {
                    UIApplication.shared.openURL(appSettings)
                }
                return
            }

        } else {
            switch PermissionScope().statusNotifications() {
            case .unknown :
                showPermissionAsk2()
            case .unauthorized:
                
                return
            case .authorized:

                return
            case .disabled:
                
                return
            }

        }
    }
    
    func showPermissionAsk2() {
        multiPscope.closeButton.setTitle("X", for: UIControlState())
        
        multiPscope.addPermission(NotificationsPermission(notificationCategories: nil),
            message: "Werden benötigt um Benachrichtigungen zu zeigen!")
        
        multiPscope.show(
            { finished, results in
                print("got results \(results) \(finished)")
            },
            cancelled: { results in
                print("thing was cancelled")
            }
        )

    }

    
}
