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
    
    var feedbackString = ""
    
    @IBOutlet var topView: UIView!
    @IBOutlet var tableView: UITableView!
    
    var refreshControll:UIRefreshControl!
    
    override func viewDidAppear(_ animated: Bool) {
        self.edgesForExtendedLayout = UIRectEdge()
        if revealViewController() != nil {
            view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
    }
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        if(section == 0) {
            return 2
        } else if(section == 1) {
            return 2
        } else if(section == 2) {
            return 1
        } else if(section == 3) {
            return 3
        } else if(section == 4) {
            return 1
        }
        return 0
    }
    
    func numberOfSections(in tableView: UITableView) -> Int {
        return 5
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        let cell: UITableViewCell = self.tableView.dequeueReusableCell(withIdentifier: "cell")!
        
        cell.selectionStyle = UITableViewCellSelectionStyle.default
        if((indexPath as NSIndexPath).section == 0) {
            
            if((indexPath as NSIndexPath).row == 0) {
                cell.textLabel?.text = "Nutzername"
                cell.imageView!.image = UIImage(named: "user")
                cell.detailTextLabel?.text = Prefs.getGoodUsername()
                cell.selectionStyle = UITableViewCellSelectionStyle.none
            } else {
                cell.textLabel?.text = "Abmelden"
                cell.imageView!.image = UIImage(named: "logout")
                cell.detailTextLabel?.text = "Melde dich ab, um den Benutzer zu wechseln."
                cell.accessoryType = UITableViewCellAccessoryType.disclosureIndicator
            }
            
        } else if ((indexPath as NSIndexPath).section == 1) {
            
            if((indexPath as NSIndexPath).row == 0) {
                cell.textLabel?.text = "Kontakt"
                cell.imageView!.image = UIImage(named: "contact")
                cell.detailTextLabel?.text = "Wir beantworten gerne deine Fragen."
                cell.accessoryType = UITableViewCellAccessoryType.disclosureIndicator
            } else {
                cell.textLabel?.text = "Instant Feedback"
                cell.imageView!.image = UIImage(named: "instantfeedback")
                cell.detailTextLabel?.text = "Teile uns deine Ideen und Wünsche mit!"
                cell.accessoryType = UITableViewCellAccessoryType.disclosureIndicator
            }

        } else if ((indexPath as NSIndexPath).section == 2) {
            
            if((indexPath as NSIndexPath).row == 0) {
                cell.textLabel?.text = "Erinnerungen"
                cell.imageView!.image = UIImage(named: "reminder")
                cell.detailTextLabel?.text = "Einstellungen für die Erinnerungen"
                cell.accessoryType = UITableViewCellAccessoryType.disclosureIndicator
            }
            
        } else if ((indexPath as NSIndexPath).section == 3) {
            if((indexPath as NSIndexPath).row == 0) {
                
                cell.textLabel?.text = "Bewerte Martinshare 🎉"
                cell.imageView!.image = UIImage(named: "rate")
                cell.detailTextLabel?.text = "Wir würden uns über deine Bewertung freuen!"
                cell.accessoryType = UITableViewCellAccessoryType.disclosureIndicator
                
            } else if((indexPath as NSIndexPath).row == 1) {
                
                cell.textLabel?.text = "Berechtigungen"
                cell.imageView!.image = UIImage(named: "security")
                cell.detailTextLabel?.text = ""
                cell.accessoryType = UITableViewCellAccessoryType.disclosureIndicator
                
            } else if((indexPath as NSIndexPath).row == 2) {
                
                cell.textLabel?.text = "Info"
                cell.imageView!.image = UIImage(named: "info")
                cell.detailTextLabel?.text = ""
                cell.accessoryType = UITableViewCellAccessoryType.disclosureIndicator
                
            }
        }
        
        if ((indexPath as NSIndexPath).section == 4) {
            let cell2 = self.tableView.dequeueReusableCell(withIdentifier: "infoCell")! as! SettingsInfoCell
            cell2.selectionStyle = UITableViewCellSelectionStyle.none
            
            if let version = Bundle.main.infoDictionary?["CFBundleShortVersionString"] as? String {
                cell2.versionLbl.text = "Version \(version)"

            }
            return cell2
        }
        
        return cell
        
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        
        if((indexPath as NSIndexPath).section == 4) {
            return 100
        } else {
            return 50
        }
    }
    
    
    func tableView(_ tableView: UITableView, titleForHeaderInSection section: Int) -> String? {
        if(section == 0) {
            return "Benutzer"
        } else if (section == 1) {
            return "Kontakt"
        } else if (section == 2) {
            return "Erinnerungen"
        } else if (section == 3) {
            return "Info"
        }  else if (section == 4) {
            return ""
        }
        return ""
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        
        
        if((indexPath as NSIndexPath).section == 0) {
            
            if((indexPath as NSIndexPath).row == 0) {
                
                
            } else {
                
                let alert = BPCompatibleAlertController(title: "Abmelden", message: "Bist du sicher, dass du dich Abmelden möchtest?", alertStyle: .alert)
                
                alert.addAction(BPCompatibleAlertAction(title: "Abbrechen", actionStyle: .cancel, handler: { action in
                    
                }))
                
                let alert2 = BPCompatibleAlertController(title: "Keine Internetverbindung", message: "Bitte überprüfe deine Internetverbindung", alertStyle: .alert)
                
                alert2.addAction(BPCompatibleAlertAction(title: "OK", actionStyle: .cancel, handler: { action in
                    
                }))
                
                alert.addAction(BPCompatibleAlertAction(title: "Abmelden", actionStyle: .destructive, handler: { action in
                    
                    self.showWaitOverlayWithText("Du wirst abgemeldet...")
                    self.view.isUserInteractionEnabled = false
                    MartinshareAPI.ausloggen(Prefs.getUsername(), key: Prefs.getKey(),
                        
                        failed: {() in
                            self.view.isUserInteractionEnabled = true
                            self.removeAllOverlays()
                            alert2.presentFrom(self, animated: true, completion: nil)
                            
                        },
                        
                        erfolg: {() in
                            self.navigationController?.popViewController(animated: true)
                            self.view.isUserInteractionEnabled = true
                            self.removeAllOverlays()
                            UIApplication.shared.cancelAllLocalNotifications()
                            self.performSegue(withIdentifier: "startStart", sender: nil)
                            
                            
                    })
                    
                }))
                
                alert.presentFrom(self, animated: true, completion: nil)
                
            }
            
        } else if ((indexPath as NSIndexPath).section == 1) {
            if((indexPath as NSIndexPath).row == 0) {
                contact()
            } else {
                instantFeedback()
                
            }
        } else if((indexPath as NSIndexPath).section == 2) {
            if((indexPath as NSIndexPath).row == 0) {
                performSegue(withIdentifier: "setNotification", sender: nil)
            }
            
        } else if ((indexPath as NSIndexPath).section == 3) {
            if((indexPath as NSIndexPath).row == 0) {
                UIApplication.shared.openURL(URL(string: "https://ios.martinshare.com/")!)
            } else if((indexPath as NSIndexPath).row == 1) {
                MSPermissions().showPermissions(true)
            } else if((indexPath as NSIndexPath).row == 2) {
                performSegue(withIdentifier: "showInfo", sender: nil)
            }
        }
    
        self.tableView.deselectRow(at: indexPath, animated: true)
    }
    
    func instantFeedback() {
        let alert = BPCompatibleAlertController(title: "Sende Martinshare Feedback", message: "Hast du Probleme mit Martinshare? \nVorschläge? \nAnregungen? \n Teile sie uns mit! \n(max. 500 Zeichen)", alertStyle: .alert)
        
        alert.addTextFieldWithConfigurationHandler({(textField) in
            textField.placeholder = "Nachricht"
            textField.autocorrectionType = .yes
            textField.text = self.feedbackString
        })
        
        alert.addAction(BPCompatibleAlertAction(title: "Senden", actionStyle:
            .default, handler: { action in
                self.feedbackString = (alert.textFieldAtIndex(0)?.text!)!
                MartinshareAPI.sendFeedback(Prefs.getGoodUsername(), key: Prefs.getKey(), message: (alert.textFieldAtIndex(0)?.text)!, feedbackprotocol: self as FeedbackProtocol)
        }))
        
        alert.addAction(BPCompatibleAlertAction(title: "Später", actionStyle: .cancel, handler: { action in
            self.feedbackString = (alert.textFieldAtIndex(0)?.text!)!
        }))
        
        alert.presentFrom(self, animated: true, completion: nil)
    }
    
    func contact() {
        let version = Bundle.main.infoDictionary?["CFBundleShortVersionString"] as? String
        let emailTitle = "Kontakt"
        let messageBody = " \n \n \n \n Martinshare v\(version!) - iOS - \(Prefs.getGoodUsername())"
        let toRecipents = ["info@martinshare.com"]
        let mc: MFMailComposeViewController = MFMailComposeViewController()
        mc.mailComposeDelegate = self
        mc.setSubject(emailTitle)
        mc.setMessageBody(messageBody, isHTML: false)
        mc.setToRecipients(toRecipents)
        self.present(mc, animated: true, completion: nil)
    }
    
    func mailComposeController(_ controller:MFMailComposeViewController, didFinishWith result:MFMailComposeResult, error:Error?) {
        switch result.rawValue {
        case MFMailComposeResult.cancelled.rawValue:
            print("Mail cancelled")
        case MFMailComposeResult.saved.rawValue:
            print("Mail saved")
        case MFMailComposeResult.sent.rawValue:
            
            let alert1 = BPCompatibleAlertController(title: "Erfolg", message: "Die Nachricht wurde erfolgreich verschickt", alertStyle: .alert)
            
            alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.default, handler: nil))
            alert1.presentFrom(self, animated: true, completion: nil)
            
        case MFMailComposeResult.failed.rawValue:
            
            let alert1 = BPCompatibleAlertController(title: "Fehlgeschlagen", message: "Das Senden der Nachricht ist fehlgeschlagen, bitte probiere es erneut; Fehler: \(error!.localizedDescription)", alertStyle: .alert)
            
            alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.default, handler: nil))
            alert1.presentFrom(self, animated: true, completion: nil)
            
        default:
            break
        }
        self.dismiss(animated: true, completion: nil)
    }
    
    func enableUserInteraction() {
        topView.isUserInteractionEnabled = true
        removeAllOverlays()
        self.refreshControll.endRefreshing()
    }
    
    func disableUserInteraction() {
        topView.isUserInteractionEnabled = false
    }

}

extension EinstellungenController: FeedbackProtocol {
    
    
    func startedConnection() {
        disableUserInteraction()
        showWaitOverlayWithText("Einen Moment Nachricht wird übermittelt...")
    }
    
    func notLoggedIn() {
        
        let alert = BPCompatibleAlertController(title: "Nicht Eingeloggt", message: "Bitte logge dich erneut ein.", alertStyle: .alert)
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.default, handler: nil))
        
        alert.presentFrom(self, animated: true, completion: nil)
        
        enableUserInteraction()
    }
    
    func unknownError() {
        
        let alert = BPCompatibleAlertController(title: "Fehler", message: "Bitte probiere es erneut", alertStyle: .alert)
        
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .default, handler: nil))
        alert.presentFrom(self, animated: true, completion: nil)
        
        enableUserInteraction()
    }
    
    func sent() {
        enableUserInteraction()
        resignFirstResponder()
        
        let alert = BPCompatibleAlertController(title: "Super!", message: "Feedback wurde übermittelt!", alertStyle: .alert)
        
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .default, handler: nil))
        alert.presentFrom(self, animated: true, completion: nil)
        
        
        feedbackString = ""
        
    }
    
    func noInternetForSending() {
        
        let alert = BPCompatibleAlertController(title: "Keine Internetverbindung", message: "Bitte sende dein Feedback erneut ab. ", alertStyle: .alert)
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .default, handler: nil))
        
        alert.presentFrom(self, animated: true, completion: nil)
        
        enableUserInteraction()
        
    }
    
    
    func error(_ textshow: String) {
        let alert1 = BPCompatibleAlertController(title: "Martinshare meldet:", message: textshow, alertStyle: .alert)
        alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.default, handler: nil))
        alert1.presentFrom(self, animated: true, completion: nil)
        
        enableUserInteraction()
    }
    
}

    

