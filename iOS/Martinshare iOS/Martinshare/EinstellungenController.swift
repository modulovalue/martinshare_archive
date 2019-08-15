//
//  EinstellungenController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 25.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import UIKit
import MessageUI

class EinstellungenController: UIViewController, UITableViewDelegate, UITableViewDataSource, MFMailComposeViewControllerDelegate {
    
    @IBOutlet
    var tableView: UITableView!

    override func viewDidLoad() {
        super.viewDidLoad()
        
        tableView.rowHeight = 50
        
    }
    
    
    func tableView(tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return 5
    }
    
    func tableView(tableView: UITableView, cellForRowAtIndexPath indexPath: NSIndexPath) -> UITableViewCell {
        var cell:UITableViewCell = self.tableView.dequeueReusableCellWithIdentifier("cell") as! UITableViewCell

        switch indexPath.row {
        case 0:
            cell.textLabel?.text = "Nutzername"
            cell.detailTextLabel?.text = Prefs.getGoodUsername()
            cell.selectionStyle = UITableViewCellSelectionStyle.None
        case 1:
            cell.textLabel?.text = "Vertretungsplan"
            cell.detailTextLabel?.text = "Einstellungen für die Vertretungsplanmarkierung"
            
            cell.accessoryType = UITableViewCellAccessoryType.DisclosureIndicator
        case 2:
            cell.textLabel?.text = "Logout"
            cell.detailTextLabel?.text = "Logge dich aus, um den Benutzer zu wechseln."
            cell.accessoryType = UITableViewCellAccessoryType.DisclosureIndicator
        case 3:
            cell.textLabel?.text = "Info"
            cell.detailTextLabel?.text = ""
            cell.accessoryType = UITableViewCellAccessoryType.DisclosureIndicator
        case 4:
            cell.textLabel?.text = "Kontakt"
            cell.detailTextLabel?.text = "Fragen, Vorschläge, Probleme?"
            cell.accessoryType = UITableViewCellAccessoryType.DisclosureIndicator
        default:
            break
        }
        
        return cell
    }
    
    func tableView(tableView: UITableView, titleForHeaderInSection section: Int) -> String? {
        if(section == 0) {
            return "Einstellungen"
        } else {
            return ""
        }
        
    }
    
    func tableView(tableView: UITableView, didSelectRowAtIndexPath indexPath: NSIndexPath) {
        
        switch indexPath.row {
        case 0:
            break
        case 1:
            performSegueWithIdentifier("markierung", sender: nil)
            
        case 2:
            
            
            var alert = BPCompatibleAlertController(title: "Ausloggen", message: "Bist du sicher, dass du Ausloggen möchtest?", alertStyle: .Alert)
            
                alert.addAction(BPCompatibleAlertAction(title: "Abbrechen", actionStyle: .Cancel, handler: { action in
                
            }))
            
            var alert2 = BPCompatibleAlertController(title: "Keine Internetverbindung", message: "Bitte überprüfe deine Internetverbindung", alertStyle: .Alert)
            
                alert2.addAction(BPCompatibleAlertAction(title: "OK", actionStyle: .Cancel, handler: { action in
                
            }))
            
            alert.addAction(BPCompatibleAlertAction(title: "Ausloggen", actionStyle: .Destructive, handler: { action in
                
                MartinshareAPI.ausloggen(Prefs.getUsername(), key: Prefs.getKey(),
                    
                    failed: {() in
                        alert2.presentFrom(self, animated: true, completion: nil)
                    },
                
                    erfolg: {() in
                        self.navigationController?.dismissViewControllerAnimated(true, completion: nil)
                        self.performSegueWithIdentifier("startStart", sender: nil)
                    })
                
            }))
            
            alert.presentFrom(self, animated: true, completion: nil)
            
        case 3:
            performSegueWithIdentifier("showInfo", sender: nil)
        case 4:
            contact()
        default:
            println("DEFAULT")
        }
    }
    
    func contact() {
        var emailTitle = "Kontakt"
        var messageBody = " \n \n \n \n Martinshare - iOS - \(Prefs.getGoodUsername())"
        var toRecipents = ["info@martinshare.com"]
        var mc: MFMailComposeViewController = MFMailComposeViewController()
        mc.mailComposeDelegate = self
        mc.setSubject(emailTitle)
        mc.setMessageBody(messageBody, isHTML: false)
        mc.setToRecipients(toRecipents)
        self.presentViewController(mc, animated: true, completion: nil)
    }
    
    func mailComposeController(controller:MFMailComposeViewController, didFinishWithResult result:MFMailComposeResult, error:NSError) {
        switch result.value {
        case MFMailComposeResultCancelled.value:
            println("Mail cancelled")
        case MFMailComposeResultSaved.value:
            println("Mail saved")
        case MFMailComposeResultSent.value:
            
            var alert1 = BPCompatibleAlertController(title: "Erfolg", message: "Die Nachricht wurde erfolgreich verschickt", alertStyle: .Alert)
            
            alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.Default, handler: nil))
            alert1.presentFrom(self, animated: true, completion: nil)
            
        case MFMailComposeResultFailed.value:
            
            var alert1 = BPCompatibleAlertController(title: "Fehlgeschlagen", message: "Das Senden der Nachricht ist fehlgeschlagen, bitte probiere es erneut; Fehler: \(error.localizedDescription)", alertStyle: .Alert)
            
            alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.Default, handler: nil))
            alert1.presentFrom(self, animated: true, completion: nil)
            
        default:
            break
        }
        self.dismissViewControllerAnimated(true, completion: nil)
    }

}
    

