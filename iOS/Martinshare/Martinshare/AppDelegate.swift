//
//  AppDelegate.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 25.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import UIKit
import CoreData
import Parse

@UIApplicationMain
class AppDelegate: UIResponder, UIApplicationDelegate, CheckPushProtocol {

    var window: UIWindow?

    func application(_ application: UIApplication, didFinishLaunchingWithOptions launchOptions: [UIApplicationLaunchOptionsKey: Any]?) -> Bool {
        

        Parse.setApplicationId("1RpRz1obmjV58rUwZPATjbQVdwUvwoLWuUf5oDhQ",
            clientKey: "7gwZubUrkTQqMXRejExhle4Q7ApORatypArsJ9aB")
        
        if application.applicationState != UIApplicationState.background {
            let preBackgroundPush = !application.responds(to: #selector(getter: UIApplication.backgroundRefreshStatus))
            let oldPushHandlerOnly = !self.responds(to: #selector(UIApplicationDelegate.application(_:didReceiveRemoteNotification:fetchCompletionHandler:)))
            var pushPayload = false
            if let options = launchOptions {
                pushPayload = options[UIApplicationLaunchOptionsKey.remoteNotification] != nil
            }
            
            if (preBackgroundPush || oldPushHandlerOnly || pushPayload) {
                PFAnalytics.trackAppOpened(launchOptions: launchOptions)
            }
        }
        
        // Extract the notification data
        if let notificationPayload = launchOptions?[UIApplicationLaunchOptionsKey.remoteNotification] as? NSDictionary {
            
            if let url = notificationPayload["url"] as? NSString {
                UIApplication.shared.openURL(URL(string: url as String)!)
                print("didfinishlaunching 6")
            }
            
        }
    
        return true
    
    }
    
    
    func application(_ application: UIApplication, didReceiveRemoteNotification userInfo: [AnyHashable: Any]) {
        
        PFPush.handle(userInfo)
    
        if (application.applicationState == UIApplicationState.inactive || application.applicationState == UIApplicationState.background ) {
    
            if let url = userInfo["url"] as? NSString {
            
                if(UebersichtController.uebersichtController != nil ) {
                
                    let alert = BPCompatibleAlertController(title: "URL öffnen", message: "URL \(url) öffnen?", alertStyle: .alert)
                    alert.addAction(BPCompatibleAlertAction(title: "Öffnen", actionStyle: .cancel, handler: { action in
                        DispatchQueue.main.async {
                            UIApplication.shared.openURL(URL(string: url as String)!)
                        }
                    }))
                    alert.addAction(BPCompatibleAlertAction(title: "Abbrechen", actionStyle: .destructive, handler: nil))
                    alert.presentFrom(UebersichtController.uebersichtController, animated: true, completion: nil)
               
                }
                
            } else {
                
                if(UebersichtController.uebersichtController != nil ) {
                    UebersichtController.uebersichtController.aktualisiereEintraege(true, afterAktualisiertOderAktuell: {})
                }
                
            }
        }
        
        if application.applicationState == UIApplicationState.inactive {
            PFAnalytics.trackAppOpened(withRemoteNotificationPayload: userInfo)
        }
    }
    
    func application(_ application: UIApplication, didRegisterForRemoteNotificationsWithDeviceToken deviceToken: Data) {
       
        PFInstallation.current().setDeviceTokenFrom(deviceToken)
        PFInstallation.current().saveEventually()
    }
    
    
    func application(_ application: UIApplication, handleActionWithIdentifier identifier: String?, forRemoteNotification userInfo: [AnyHashable: Any], completionHandler: @escaping () -> Void) {
        
    }
    
    func application(_ application: UIApplication, handleActionWithIdentifier identifier: String?, forRemoteNotification userInfo: [AnyHashable: Any], withResponseInfo responseInfo: [AnyHashable: Any], completionHandler: @escaping () -> Void) {
        
    }
    
    func application(_ application: UIApplication, didFailToRegisterForRemoteNotificationsWithError error: Error) {
        if error.code == 3010 {
            print("Push notifications are not supported in the iOS Simulator.")
        } else {
            print("application:didFailToRegisterForRemoteNotificationsWithError: %@", error)
        }
    }
    
    
    func applicationDidFinishLaunching(_ application: UIApplication) {
        
    }

    
    func applicationDidBecomeActive(_ application: UIApplication) {
        UIApplication.shared.applicationIconBadgeNumber = 0
    }
   

    func applicationWillTerminate(_ application: UIApplication) {
        // Called when the application is about to terminate. Save data if appropriate. See also applicationDidEnterBackground:.
        // Saves changes in the application's managed object context before the application terminates.
        self.saveContext()
    }

    // MARK: - Core Data stack

    lazy var applicationDocumentsDirectory: URL = {
        // The directory the application uses to store the Core Data store file. This code uses a directory named "com.modestasv.Martinshare" in the application's documents Application Support directory.
        let urls = FileManager.default.urls(for: .documentDirectory, in: .userDomainMask)
        return urls[urls.count-1] 
    }()

    lazy var managedObjectModel: NSManagedObjectModel = {
        // The managed object model for the application. This property is not optional. It is a fatal error for the application not to be able to find and load its model.
        let modelURL = Bundle.main.url(forResource: "Martinshare", withExtension: "momd")!
        return NSManagedObjectModel(contentsOf: modelURL)!
    }()

    lazy var persistentStoreCoordinator: NSPersistentStoreCoordinator = {
        // The persistent store coordinator for the application. This implementation creates and return a coordinator, having added the store for the application to it. This property is optional since there are legitimate error conditions that could cause the creation of the store to fail.
        // Create the coordinator and store
        let coordinator = NSPersistentStoreCoordinator(managedObjectModel: self.managedObjectModel)
        let url = self.applicationDocumentsDirectory.appendingPathComponent("PROJECTNAME.sqlite")
        var failureReason = "There was an error creating or loading the application's saved data."
        do {
            try coordinator.addPersistentStore(ofType: NSSQLiteStoreType, configurationName: nil, at: url, options: nil)
        } catch {
            // Report any error we got.
            var dict = [String: AnyObject]()
            dict[NSLocalizedDescriptionKey] = "Failed to initialize the application's saved data" as AnyObject?
            dict[NSLocalizedFailureReasonErrorKey] = failureReason as AnyObject?
            
            dict[NSUnderlyingErrorKey] = error as NSError
            let wrappedError = NSError(domain: "YOUR_ERROR_DOMAIN", code: 9999, userInfo: dict)
            // Replace this with code to handle the error appropriately.
            // abort() causes the application to generate a crash log and terminate. You should not use this function in a shipping application, although it may be useful during development.
            NSLog("Unresolved error \(wrappedError), \(wrappedError.userInfo)")
            abort()
        }
        
        return coordinator
    }()
    
    lazy var managedObjectContext: NSManagedObjectContext = {
        // Returns the managed object context for the application (which is already bound to the persistent store coordinator for the application.) This property is optional since there are legitimate error conditions that could cause the creation of the context to fail.
        let coordinator = self.persistentStoreCoordinator
        var managedObjectContext = NSManagedObjectContext(concurrencyType: .mainQueueConcurrencyType)
        managedObjectContext.persistentStoreCoordinator = coordinator
        return managedObjectContext
    }()

    // MARK: - Core Data Saving support

    func saveContext () {
        if managedObjectContext.hasChanges {
            do {
                try managedObjectContext.save()
            } catch {
                // Replace this implementation with code to handle the error appropriately.
                // abort() causes the application to generate a crash log and terminate. You should not use this function in a shipping application, although it may be useful during development.
                let nserror = error as NSError
                NSLog("Unresolved error \(nserror), \(nserror.userInfo)")
                abort()
            }
        }
    }
    
    
    
    
    
    
    func success() {
        print("PUSH Success")
    }
    
    func noInternetConnection() {
        print("nointernetconnection PUSH")
    }
    
    func alreadyRight() {
        print("pushID already ok")
    }
    
    func unknownError() {
        print("unknown error PUSH")
    }
    
    func wrongCredentials() {
        print("wrong credentials PUSH")
    }

}

