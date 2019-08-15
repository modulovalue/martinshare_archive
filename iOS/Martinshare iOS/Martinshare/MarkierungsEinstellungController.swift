//
//  MarkierungsEinstellungController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 28.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation
import UIKit

class MarkierungsEinstellungController: UITableViewController, UIAlertViewDelegate {

    @IBAction func doneBtn(sender: AnyObject) {
        navigationController?.dismissViewControllerAnimated(true, completion: nil)
    }
    
    override func tableView(tableView: UITableView, willDisplayCell cell: UITableViewCell, forRowAtIndexPath indexPath: NSIndexPath) {
        
        switch indexPath.row {
        case 0:
            cell.detailTextLabel?.text = Prefs.getVertretungsplanMarkierung()
        case 1:
            cell.detailTextLabel?.text = Prefs.getVertretungsplanMarkierungFarbe()
            cell.detailTextLabel?.textColor = UIColor(rgba: Prefs.getVertretungsplanMarkierungFarbe())
        case 2:
            cell.detailTextLabel?.text = Prefs.getVertretungsplanMarkierungSize()
        default:
            break
        }
    }
    
    func alertView(alertView: UIAlertView, clickedButtonAtIndex buttonIndex: Int) {
        
        if(alertView == alertVertre) {
            switch(buttonIndex) {
            case 0:
                return
            case 1:
                let text = alertVertre.textFieldAtIndex(0)!.text
                
                if(text != "" && text != nil) {
                    Prefs.putVertretungsplanMarkierung(text)
                    tableView.reloadData()
                } else {
                Prefs.putVertretungsplanMarkierung("--")
                tableView.reloadData()
                }
            default:
                return
            }
        }
        
        if(alertView == alertVertreSize) {
            switch(buttonIndex) {
            case 0:
                return
            case 1:
                print("\(alertVertreSize.textFieldAtIndex(0)!.text)")
                
                var size: Int
                var zahl: Int? = alertVertreSize.textFieldAtIndex(0)!.text.toInt()
                
                if (zahl != nil) {
                    if zahl! > Prefs.markierungsSizeUpperLimit {
                        size = Prefs.markierungsSizeUpperLimit
                    } else if zahl! < Prefs.markierungsSizeLowerLimit {
                        size = Prefs.markierungsSizeLowerLimit
                    } else {
                        size = zahl!
                    }
                
                    Prefs.putVertretungsplanMarkierungSize("\(size)")
                    println(Prefs.getVertretungsplanMarkierungSize())
                    tableView.reloadData()
                
                } else {
                                        
                    //alert.title = "Ungültige Eingabe"
                    //alert.reloadInputViews()
                }

            default:
                return
            }
        }
        println(buttonIndex)
        
    }
    
    var alertVertre = UIAlertView()
    var alertVertreSize = UIAlertView()
    
    override func tableView(tableView: UITableView, didSelectRowAtIndexPath indexPath: NSIndexPath) {
        
        switch indexPath.row {
        case 0:
            
            
            
            alertVertre.title = "Vertretungsplanmarkierung"
            alertVertre.message = "Gib die gewünschte Vertretungsplanmarkierung ein (Großschreibung beachten):"
            
            alertVertre.addButtonWithTitle("Abbrechen")
            alertVertre.addButtonWithTitle("OK")
            alertVertre.delegate = self
            
            alertVertre.alertViewStyle = UIAlertViewStyle.PlainTextInput
            alertVertre.textFieldAtIndex(0)?.placeholder =
                Prefs.getVertretungsplanMarkierung()
            alertVertre.show()
            
            
//            
//            var alert = BPCompatibleAlertController(title: "Vertretungsplanmarkierung", message: "Gib die gewünschte Vertretungsplanmarkierung ein (Großschreibung beachten):", alertStyle: BPCompatibleAlertControllerStyle.Alert)
//            
//            alert.addAction(BPCompatibleAlertAction(title: "Abbrechen", actionStyle: BPCompatibleAlertAction., handler: nil))
//            
//            alert.addTextFieldWithConfigurationHandler({(textField: UITextField!) in
//                textField.placeholder = "Klasse"
//                textField.text = Prefs.getVertretungsplanMarkierung()
//                
//                alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .Default, handler: { action in
//                    
//                    Prefs.putVertretungsplanMarkierung(textField.text)
//                    println(Prefs.getVertretungsplanMarkierung())
//                    tableView.reloadData()
//                    
//                }))
//            })
//            
//            alert.presentFrom(self, animated: true, completion: nil)
//            
        case 1:
            
            var colorList = NSBundle.mainBundle().pathForResource("colorPalette", ofType: "plist")
            let data = NSArray(contentsOfFile: colorList!)!
            
            var laenge = data.count
            
            var randomnr = arc4random_uniform(UInt32(laenge))
    
            Prefs.putVertretungsplanMarkierungFarbe(data[Int(randomnr)] as! String)
            
            println(Prefs.getVertretungsplanMarkierungFarbe())
            tableView.reloadData()
        case 2:
            
            alertVertreSize.title = "Textgröße"
            alertVertreSize.message = "Gib die gewünschte Textgröße ein: \n \(Prefs.markierungsSizeLowerLimit) bis \(Prefs.markierungsSizeUpperLimit) \n Standart: \(Prefs.standartMarkierungsSize)"
            
            alertVertreSize.addButtonWithTitle("Abbrechen")
            alertVertreSize.addButtonWithTitle("OK")
            alertVertreSize.delegate = self
            
            alertVertreSize.alertViewStyle = UIAlertViewStyle.PlainTextInput
            alertVertreSize.textFieldAtIndex(0)?.keyboardType = UIKeyboardType.NumberPad
            alertVertre.textFieldAtIndex(0)?.placeholder =
                Prefs.getVertretungsplanMarkierungSize()
            alertVertreSize.show()

            
            
//            var alert = BPCompatibleAlertController(title: "Textgröße", message: "Gib die gewünschte Textgröße ein: \n \(Prefs.markierungsSizeLowerLimit) bis \(Prefs.markierungsSizeUpperLimit) \n Standart: \(Prefs.standartMarkierungsSize)", alertStyle: .Alert)
//            
//            alert.addAction(BPCompatibleAlertAction(title: "Abbrechen", actionStyle: .Cancel, handler: nil))
//            
//            alert.addTextFieldWithConfigurationHandler({(textField: UITextField!) in
//                textField.keyboardType = .NumberPad
//                textField.text = Prefs.getVertretungsplanMarkierungSize()
//                
//                alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .Default, handler: { action in
//                    
//                    
//                    var size: Int
//                    var zahl: Int? = textField.text.toInt()
//                    
//                    if (zahl != nil) {
//                        if zahl! > Prefs.markierungsSizeUpperLimit {
//                            size = Prefs.markierungsSizeUpperLimit
//                        } else if zahl! < Prefs.markierungsSizeLowerLimit {
//                            size = Prefs.markierungsSizeLowerLimit
//                        } else {
//                            size = zahl!
//                        }
//                        
//                        Prefs.putVertretungsplanMarkierungSize("\(size)")
//                        println(Prefs.getVertretungsplanMarkierungSize())
//                        tableView.reloadData()
//
//                    } else {
//                        
//                        //alert.title = "Ungültige Eingabe"
//                        //alert.reloadInputViews()
//                    }
//                    
//                }))
//            })
//            
//            alert.presentFrom(self, animated: true, completion: nil)
            return
        default:
            println("DEFAULT")
        }
    }
    
    
    
}