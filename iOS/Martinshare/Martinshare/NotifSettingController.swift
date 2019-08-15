//
//  NotifSettingController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 06.02.16.
//  Copyright © 2016 ModestasV Studios. All rights reserved.
//

import Foundation
import UIKit

class NotifSettingController: UIViewController {

    //Array with Possible notification data
    static let notifData = ["dayNotification", "arbeitNotification"]
    static let notifTextOn =
        ["Du wirst an Hausaufgaben, Arbeiten und Sonstiges 1 Tag vorher erinnert. Bitte wähle die Uhrzeit.",
        "Du wirst an Arbeiten 3 Tage vorher erinnert. Bitte wähle die Uhrzeit."]
    static let notifTextOff =
        ["Du wirst an Hausaufgaben, Arbeiten und Sonstiges 1 Tag vorher nicht erinnert.",
        "Du wirst an Arbeiten 3 Tage vorher nicht erinnert."]
    
    //Which data in array should be modified
    static var notifVal = 0
    
    
    override func viewWillAppear(_ animated: Bool) {
        
        print("ViewWillAPpear")
        let notif = Prefs.getNotifInfo(NotifSettingController.notifData[NotifSettingController.notifVal])
 
        firstSwitch.isOn = notif.active
        
        let components =  (Calendar.current as NSCalendar).components([.hour, .minute], from: Date())
        components.hour = notif.notifHour
        components.minute = notif.notifMinute
        firstPicker.setDate(Calendar.current.date(from: components)!, animated: false)

        setText()

    }
    
    @IBAction func backButton(_ sender: AnyObject) {
        navigationController?.dismiss(animated: true, completion: nil)
    }
    
    @IBAction func save(_ sender: AnyObject) {
        
        let notifSettings = NotificationPrefContainer(
            hour: (Calendar.current as NSCalendar).component(NSCalendar.Unit.hour, from: firstPicker.date),
            minute: (Calendar.current as NSCalendar).component(NSCalendar.Unit.minute, from: firstPicker.date),
            active: firstSwitch.isOn
        )
        
        Prefs.putNotifInfo(NotifSettingController.notifData[NotifSettingController.notifVal], notifInfo: notifSettings)

        NotificationPrefContainer.registerNotifications(Prefs.getEintraegeNSArray())
        
        navigationController?.dismiss(animated: true, completion: nil)
    }
    
    func setText() {
        if(firstSwitch.isOn) {
            firstText.text = NotifSettingController.notifTextOn[NotifSettingController.notifVal]
        } else {
            firstText.text = NotifSettingController.notifTextOff[NotifSettingController.notifVal]
        }
    }
    
    
    @IBOutlet weak var firstText: UILabel!
    
    @IBOutlet weak var firstPicker: UIDatePicker!
    
    @IBOutlet weak var firstSwitch: UISwitch!
    
    @IBAction func firstSwitchChanged(_ sender: AnyObject) {
        setText()
    }

    static func setNotifValVal(_ str: String) {
        print("ok")
        if "dayallnotif" == str {
            NotifSettingController.notifVal = 0
        } else if "threedayanotif" == str {
            NotifSettingController.notifVal = 1
        } else {
            print("error")
        }
    
    }
}
