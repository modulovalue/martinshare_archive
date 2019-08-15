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

    @IBAction func doneBtn(_ sender: AnyObject) {
        navigationController?.dismiss(animated: true, completion: nil)
    }
    
    override func tableView(_ tableView: UITableView, willDisplay cell: UITableViewCell, forRowAt indexPath: IndexPath) {
        
        switch (indexPath as NSIndexPath).row {
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
    
    func alertView(_ alertView: UIAlertView, clickedButtonAt buttonIndex: Int) {
        
        if(alertView == alertVertre) {
            switch(buttonIndex) {
            case 0:
                return
            case 1:
                let text = alertVertre.textField(at: 0)!.text
                
                if(text != "" && text != nil) {
                    Prefs.putVertretungsplanMarkierung(text!)
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
                print("\(alertVertreSize.textField(at: 0)!.text)")
                
                var size: Int
                let zahl: Int? = Int(alertVertreSize.textField(at: 0)!.text!)
                
                if (zahl != nil) {
                    if zahl! > Prefs.markierungsSizeUpperLimit {
                        size = Prefs.markierungsSizeUpperLimit
                    } else if zahl! < Prefs.markierungsSizeLowerLimit {
                        size = Prefs.markierungsSizeLowerLimit
                    } else {
                        size = zahl!
                    }
                
                    Prefs.putVertretungsplanMarkierungSize("\(size)")
                    print(Prefs.getVertretungsplanMarkierungSize())
                    tableView.reloadData()
                
                } else {
                                        
                    //alert.title = "Ungültige Eingabe"
                    //alert.reloadInputViews()
                }

            default:
                return
            }
        }
        print(buttonIndex)
        
    }
    
    var alertVertre = UIAlertView()
    var alertVertreSize = UIAlertView()
    
    override func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        
        switch (indexPath as NSIndexPath).row {
        case 0:
            
            
            
            alertVertre.title = "Vertretungsplanmarkierung"
            alertVertre.message = "Gib die gewünschte Vertretungsplanmarkierung ein (Großschreibung beachten):"
            
            alertVertre.addButton(withTitle: "Abbrechen")
            alertVertre.addButton(withTitle: "OK")
            alertVertre.delegate = self
            
            alertVertre.alertViewStyle = UIAlertViewStyle.plainTextInput
            alertVertre.textField(at: 0)?.placeholder =
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
            
            let colorList = Bundle.main.path(forResource: "colorPalette", ofType: "plist")
            let data = NSArray(contentsOfFile: colorList!)!
            
            let laenge = data.count
            
            let randomnr = arc4random_uniform(UInt32(laenge))
    
            Prefs.putVertretungsplanMarkierungFarbe(data[Int(randomnr)] as! String)
            
            print(Prefs.getVertretungsplanMarkierungFarbe())
            tableView.reloadData()
        case 2:
            
            alertVertreSize.title = "Textgröße"
            alertVertreSize.message = "Gib die gewünschte Textgröße ein: \n \(Prefs.markierungsSizeLowerLimit) bis \(Prefs.markierungsSizeUpperLimit) \n Standart: \(Prefs.standartMarkierungsSize)"
            
            alertVertreSize.addButton(withTitle: "Abbrechen")
            alertVertreSize.addButton(withTitle: "OK")
            alertVertreSize.delegate = self
            
            alertVertreSize.alertViewStyle = UIAlertViewStyle.plainTextInput
            alertVertreSize.textField(at: 0)?.keyboardType = UIKeyboardType.numberPad
            alertVertre.textField(at: 0)?.placeholder =
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
            print("DEFAULT")
        }
    }
    
    
    
}
