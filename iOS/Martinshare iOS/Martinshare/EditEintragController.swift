//
//  EditEintragController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 30.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation
import UIKit

class EditEintragController: UIViewController, EintragenProtocol, UITextFieldDelegate, GetDateProtocol, UITextViewDelegate {
    
    @IBOutlet weak var navBar: UINavigationItem!

    @IBOutlet weak var typImg: UIImageView!
    
    @IBOutlet var topView: UIView!
    @IBOutlet var gestureRecog: UITapGestureRecognizer!
    
    @IBOutlet weak var typAusgeschrieben: UILabel!

    @IBOutlet weak var textViewBottomConstraint: NSLayoutConstraint!
    
    @IBOutlet weak var dateBtn: UIButton!
    
    var eintrag: EintragDataContainer!
    var ursprungsEintrag: EintragDataContainer!
    
    var previousController: ShowDetailController!
    
    @IBOutlet weak var beschreibungField: UITextView!
    
    @IBOutlet weak var fachfield: UITextField!
    
    override func viewDidLoad() {
        
        NSNotificationCenter.defaultCenter().addObserver(self, selector: "keyboardWillShow:", name: UIKeyboardWillShowNotification, object: nil)
        
        NSNotificationCenter.defaultCenter().addObserver(self, selector: "keyboardWillHide:", name: UIKeyboardWillHideNotification, object: nil)
        
        
        ursprungsEintrag = eintrag.cpy()
        setTyp()
        self.beschreibungField.text = eintrag.getBeschreibung()
        
        beschreibungField.layer.borderColor = UIColor.grayColor().colorWithAlphaComponent(0.2).CGColor
        beschreibungField.layer.borderWidth = 1.0
        beschreibungField.layer.cornerRadius = 6
        beschreibungField.clipsToBounds = true
        
        self.dateBtn.setTitle(NSDate.getDeStringDatumFromString(eintrag.datum), forState: UIControlState.Normal)
        self.fachfield.text = eintrag.getTitel()
        
    }
    
    override func touchesBegan(touches: Set<NSObject>, withEvent event: UIEvent) {
        self.view.endEditing(true)
    }
    
    func setTyp() {
        
        self.typImg.image = UIImage(named: "icon\(eintrag.typ)")
        switch eintrag.typ {
        case "h":
            typAusgeschrieben.text = "Hausaufgabe"
        case "a":
            typAusgeschrieben.text = "Arbeitstermin"
        case "s":
            typAusgeschrieben.text = "Sonstiges"
        default:
            break
        }
    }
    
    func textFieldShouldReturn(textField: UITextField) -> Bool {
        resignFirstResponder()
        return true
    }
    
    func keyboardWillShow(sender: NSNotification) {
        if let userInfo = sender.userInfo {
            if let keyboardHeight = userInfo[UIKeyboardFrameEndUserInfoKey]?.CGRectValue().size.height {
                textViewBottomConstraint.constant = keyboardHeight
                UIView.animateWithDuration(0.25, animations: { () -> Void in
                    self.view.layoutIfNeeded()
                })
            }
        }
    }
    
    func keyboardWillHide(sender: NSNotification) {
        if let userInfo = sender.userInfo {
            if let keyboardHeight = userInfo[UIKeyboardFrameEndUserInfoKey]?.CGRectValue().size.height {
                textViewBottomConstraint.constant = 94
                UIView.animateWithDuration(0.25, animations: { () -> Void in
                    self.view.layoutIfNeeded()
                })
            }
        }
    }
    
    @IBAction func changeDate(sender: AnyObject) {
        performSegueWithIdentifier("datePicker", sender: nil)
    }
    
    override func prepareForSegue(segue: UIStoryboardSegue, sender: AnyObject?) {
        if(segue.identifier == "datePicker") {
            var controller = segue.destinationViewController as! UINavigationController
            var controler = controller.topViewController as! WaehleDatumController
            controler.getDate = self
            controler.altesDatum = eintrag.datum
        }
    }
    
    @IBAction func cancel(sender: AnyObject) {
        getNewData()
        if(!ursprungsEintrag.compare(eintrag)) {
            
        
            var alert = BPCompatibleAlertController(title: "Abbrechen", message: "Deine Änderung wird nicht gespeichert. Änderung verwerfen?", alertStyle: .Alert)
            
            
            alert.addAction(BPCompatibleAlertAction(title: "Änderung verwerfen", actionStyle:
                .Destructive, handler: { action in
                
                navigationController?.dismissViewControllerAnimated(true, completion: nil)
            }))
            
            
            alert.addAction(BPCompatibleAlertAction(title: "Zurück zur Bearbeitung", actionStyle: .Cancel, handler: nil))
            alert.presentFrom(self, animated: true, completion: nil)

        } else {
            navigationController?.dismissViewControllerAnimated(true, completion: nil)
        }
    }
    
    @IBAction func typBtn(sender: AnyObject) {
        eintrag.typ = EintragDataContainer.getNextTyp(eintrag.typ)
        setTyp()
    }

    
    @IBAction func save(sender: AnyObject) {
        getNewData()
        MartinshareAPI.eintragUpdaten(Prefs.getGoodUsername(), key: Prefs.getKey(), newEintrag: eintrag.cpy(), eintragen: self)
    }
    
    func getNewData() {
        eintrag.beschreibung = beschreibungField.text
        eintrag.titel = fachfield.text
    }
    
    func enableUserInteraction() {
        topView.userInteractionEnabled = true
        removeAllOverlays()
    }
    
    func disableUserInteraction() {
        topView.userInteractionEnabled = false
    }
    
    func startedConnection() {
        disableUserInteraction()
        showWaitOverlayWithText("Einen Moment...")
    }
    
    func notLoggedIn() {
        var alert1 = BPCompatibleAlertController(title: "Nicht Eingeloggt", message: "Bitte logge dich erneut ein.", alertStyle: .Alert)
        alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.Default, handler: nil))
        alert1.presentFrom(self, animated: true, completion: nil)

        enableUserInteraction()
    }
    
    func unknownError() {
        var alert1 = BPCompatibleAlertController(title: "Fehler", message: "Bitte probiere es erneut", alertStyle: .Alert)
        alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.Default, handler: nil))
        alert1.presentFrom(self, animated: true, completion: nil)
        
        enableUserInteraction()
    }
    
    func noInternet() {
        var alert1 = BPCompatibleAlertController(title: "Keine Internetverbindung", message: "Bitte probiere es später erneut.", alertStyle: .Alert)
        alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.Default, handler: nil))
        alert1.presentFrom(self, animated: true, completion: nil)
        
        enableUserInteraction()
    }
    
    func aktualisiert() {
        enableUserInteraction()
        resignFirstResponder()
        
        var kalenderAktualisieren: ()-> Void = {
            if(KalenderController.kalenderController != nil ) {
                KalenderController.kalenderController.aktualisieren()
            }
        }
        
        var uebersichtAktualisieren: ()-> Void = { UebersichtController.uebersichtController.aktualisiereEintraege(false, afterAktualisiertOderAktuell: kalenderAktualisieren)}
        
        
        var closure: ()->Void = {
            if(self.previousController.previousController != nil) {
                self.previousController.previousController.dismissViewControllerAnimated(false, completion: uebersichtAktualisieren)
            } else {
                uebersichtAktualisieren()
            }
        }
        var popUp: ()-> Void = {self.previousController.navigationController?.dismissViewControllerAnimated(false, completion: closure)}
        
        self.navigationController?.dismissViewControllerAnimated(false, completion: popUp)

    }
    
    func putDate(formattedDate: String) {
        eintrag.datum = formattedDate
        dateBtn.setTitle(NSDate.getDeStringDatumFromString(formattedDate), forState: UIControlState.Normal)
    }
    
}


