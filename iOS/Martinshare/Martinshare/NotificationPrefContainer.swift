//
//  NotificationPrefContainer.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 06.02.16.
//  Copyright © 2016 ModestasV Studios. All rights reserved.
//

import Foundation

class NotificationPrefContainer: NSObject, NSCoding {

    var notifHour = 16
    var notifMinute = 0
    var active = true
    
    required convenience init(coder aDecoder: NSCoder) {
        self.init()
        self.notifHour              = aDecoder.decodeObject(forKey: "notifHour") as! Int
        self.notifMinute            = aDecoder.decodeObject(forKey: "notifMinute") as! Int
        self.active                 = aDecoder.decodeObject(forKey: "active") as! Bool

    }
    
    func encode(with aCoder: NSCoder) {
        aCoder.encode(self.notifHour,                  forKey: "notifHour")
        aCoder.encode(self.notifMinute,                forKey: "notifMinute")
        aCoder.encode(self.active,                     forKey: "active")
    }
    
    override init() {
        super.init()
    }

    init(hour: Int, minute: Int, active: Bool) {
        self.notifMinute = minute
        self.notifHour = hour
        self.active = active
        super.init()
    }
    

    static func registerNotifications(_ eintraegeArray: NSArray) {
    
        UIApplication.shared.cancelAllLocalNotifications()
        
        var notificationSchedulerDictionary = [Date: NotificationScheduleContainer]()
        
        let dateNow = Date()
        
        for eintrag in eintraegeArray {
        
            let dateFormatter = DateFormatter()
            dateFormatter.dateFormat = "yyyy-MM-dd"
            let entrg: EintragDataContainer = eintrag as! EintragDataContainer
            let date = dateFormatter.date(from: entrg.datum)
            
            let nsc = notificationSchedulerDictionary[date!]
            
            if(entrg.deleted as String == "0" && date?.compare(dateNow) == ComparisonResult.orderedDescending) {
                if((nsc) != nil ) {
                    nsc!.addToNSC(entrg.typ as String)
                } else {
                    notificationSchedulerDictionary[date!] = NotificationScheduleContainer().addToNSC(entrg.typ as String)
                }
            }
            
        }
        
        
        let notifInfo1 = Prefs.getNotifInfo(NotifSettingController.notifData[0])
        
        if(notifInfo1.active) {
            for(ab, ac) in notificationSchedulerDictionary {
                var components =  (Calendar.current as NSCalendar).components([.hour, .minute, .year, .day, .month], from: ab)
                components.hour = notifInfo1.notifHour
                components.minute = notifInfo1.notifMinute
                
                let firedate = (Calendar.current as NSCalendar).date(byAdding: NSCalendar.Unit.day, value: -1, to: Calendar.current.date(from: components)!, options: NSCalendar.Options())
                
                if(firedate!.compare(Date()) == ComparisonResult.orderedDescending) {
                    
                    let localNotification = UILocalNotification()
                    localNotification.fireDate = firedate
                    localNotification.soundName = UILocalNotificationDefaultSoundName // play default sound
                    localNotification.alertBody = "Morgen: \(Word.printAll(Word(temp: WordManager.arbeit, val: ac.countA), Word(temp: WordManager.hausaufgabe, val: ac.countH), Word(temp: WordManager.sonstiges, val: ac.countS)))"

                    localNotification.timeZone = TimeZone.current
                    
                    //localNotification.applicationIconBadgeNumber = UIApplication.sharedApplication().applicationIconBadgeNumber + 1
                    UIApplication.shared.scheduleLocalNotification(localNotification)
                }
            }
        }
        
        let notifInfo2 = Prefs.getNotifInfo(NotifSettingController.notifData[1])
        
        if(notifInfo2.active) {
            for(ab, ac) in notificationSchedulerDictionary {
                
                if(ac.countA >= 1) {
                    var components =  (Calendar.current as NSCalendar).components([.hour, .minute, .year, .day, .month], from: ab)
                    
                    components.hour = notifInfo2.notifHour
                    components.minute = notifInfo2.notifMinute
                    
                    let firedate = (Calendar.current as NSCalendar).date(byAdding: NSCalendar.Unit.day, value: -3, to: Calendar.current.date(from: components)!, options: NSCalendar.Options())
                    
                    if(firedate!.compare(Date()) == ComparisonResult.orderedDescending) {
                        
                        let localNotification = UILocalNotification()
                        localNotification.fireDate = firedate
                        localNotification.soundName = UILocalNotificationDefaultSoundName // play default sound
                        localNotification.alertBody = "\(Word.printAll(Word(temp: WordManager.arbeit, val: ac.countA))) in 3 Tagen"

                        localNotification.timeZone = TimeZone.current
                        //localNotification.applicationIconBadgeNumber = UIApplication.sharedApplication().applicationIconBadgeNumber + 1
                        UIApplication.shared.scheduleLocalNotification(localNotification)
                    }
                }
            }
        }
    }
    
    
    

}
