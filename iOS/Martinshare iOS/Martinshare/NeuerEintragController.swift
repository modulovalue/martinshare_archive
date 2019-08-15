//
//  NeuerEintragController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 31.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation
import UIKit
import QuartzCore

class NeuerEintragController: UIViewController, UITextFieldDelegate, GetDateProtocol, EintragenProtocol {

    @IBOutlet weak var hImage: UIImageView!
    
    @IBOutlet weak var aImage: UIImageView!

    @IBOutlet weak var sImage: UIImageView!
    
    @IBOutlet weak var fachView: UITextField!
    
    @IBOutlet weak var beschreibungView: UITextView!
    
    @IBOutlet weak var buttonView: UIButton!
    
    @IBOutlet var topView: UIView!
    
    @IBOutlet weak var textViewBottomConstraint: NSLayoutConstraint!
    
    var previousController: UINavigationController!
    
    var choosenTyp = ["h": false,"a":false,"s":false]
    
    var date: String!
    var origDate: String!
    
    override func viewDidLoad() {
        
        NSNotificationCenter.defaultCenter().addObserver(self, selector: "keyboardWillShow:", name: UIKeyboardWillShowNotification, object: nil)
        
        NSNotificationCenter.defaultCenter().addObserver(self, selector: "keyboardWillHide:", name: UIKeyboardWillHideNotification, object: nil)
        
        origDate = date
        
        buttonView.setTitle(NSDate.getDeStringDatumFromString(date), forState: UIControlState.Normal)
        
        for (typ,value) in choosenTyp {
            if(value == false) {
                animateImage(getImageFromTyp(typ), dir: value)
            }
        }
        
        beschreibungView.layer.borderColor = UIColor.grayColor().colorWithAlphaComponent(0.2).CGColor
        beschreibungView.layer.borderWidth = 1.0
        beschreibungView.layer.cornerRadius = 6
        beschreibungView.clipsToBounds = true
        
    }

    func getTypTrue()-> String {
        for (typ,value) in choosenTyp {
            if value == true {
                return typ as String
            }
        }
        return "h"
    }
    
    func setTypTrue(typ: String) {
        for (typ,value) in choosenTyp {
            choosenTyp[typ] = false
        }
        choosenTyp[typ] = true
    }
    
    func getImageFromTyp(typ: String)-> UIImageView {
        switch typ {
        case "h":
            return hImage
        case "a":
            return aImage
        case "s":
            return sImage
        default:
            return hImage
        }
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
                textViewBottomConstraint.constant = 93
                UIView.animateWithDuration(0.25, animations: { () -> Void in
                    self.view.layoutIfNeeded()
                })
            }
        }
    }
    
    func textFieldShouldReturn(textField: UITextField) -> Bool {
        beschreibungView.becomeFirstResponder()
        return false
    }
    
    @IBAction func sImageTap(sender: AnyObject) {
        animateImage(getImageFromTyp(getTypTrue()), dir: false)
        setTypTrue("s")
        animateImage(getImageFromTyp(getTypTrue()), dir: true)
    }

    @IBAction func aImageTap(sender: AnyObject) {
        animateImage(getImageFromTyp(getTypTrue()), dir: false)
        setTypTrue("a")
        animateImage(getImageFromTyp(getTypTrue()), dir: true)
    }
    
    @IBAction func hImageTap(sender: AnyObject) {
        animateImage(getImageFromTyp(getTypTrue()), dir: false)
        setTypTrue("h")
        animateImage(getImageFromTyp(getTypTrue()), dir: true)
    }
    
    @IBAction func dateBtn(sender: AnyObject) {
        performSegueWithIdentifier("datePicker", sender: nil)
    }
    
    @IBAction func saveBtn(sender: AnyObject) {
        
        let warningLbl: NSMutableArray = []
        
        if(date == nil) {
            warningLbl.addObject("das Datum")
        }
        if(fachView.text == "") {
            warningLbl.addObject("das Fach")
        }
        
        if(warningLbl.count == 0) {
            MartinshareAPI.eintragEintragen(Prefs.getGoodUsername(), key: Prefs.getKey(), typ: getTypTrue(), fach: fachView.text, beschreibung: beschreibungView.text, datum: date, eintragen: self)
        } else {
            var warn: String = ""
            
            if(warningLbl.count > 1) {
                for (index, warning) in enumerate(warningLbl) {
                    warn += warning as! String
                    if(index == 1 - warning.count) {
                        warn += " "
                    } else {
                        warn += " und "
                    }
                }
            } else {
                warn = warningLbl[0] as! String
                warn += " "
            }
            
            
            var alert = BPCompatibleAlertController(title: "Bitte fülle \(warn)aus", message: "Bitte vervollständige den Eintrag.", alertStyle: .Alert)
            
            alert.addAction(BPCompatibleAlertAction(title: "OK", actionStyle: .Default, handler: nil))
            
            alert.presentFrom(self, animated: true, completion: nil)

        }
    }

    @IBAction func cancelBtn(sender: AnyObject) {
        if(beschreibungView.text != "" || fachView.text != "" || origDate != date) {
        
            

            var alert = BPCompatibleAlertController(title: "Abbrechen", message: "Deine Eingaben werden nicht gespeichert. Eingaben verwerfen?", alertStyle: .Alert)
            
            
            alert.addAction(BPCompatibleAlertAction(title: "Eingaben verwerfen", actionStyle: .Destructive, handler: { action in
                navigationController?.dismissViewControllerAnimated(true, completion: nil)
            }))
            
            alert.addAction(BPCompatibleAlertAction(title: "Weiter bearbeiten", actionStyle: .Cancel, handler: nil))
            
            alert.presentFrom(self, animated: true, completion: nil)
            
        } else {
            navigationController?.dismissViewControllerAnimated(false, completion: nil)
        }

    }
    
    override func touchesBegan(touches: Set<NSObject>, withEvent event: UIEvent) {
        self.view.endEditing(true)
    }
    
    override func prepareForSegue(segue: UIStoryboardSegue, sender: AnyObject?) {
        if(segue.identifier == "datePicker") {
            var controller = segue.destinationViewController as! UINavigationController
            var controler = controller.topViewController as! WaehleDatumController
            controler.getDate = self
            controler.altesDatum = date
        }
    }

    
    func animateImage(view: UIImageView, dir: Bool){
        
        var alphaFrom = NSNumber(double: 1)
        var alphaTo = NSNumber(double: 0.8)
        var zoomFrom = NSNumber(double: 1)
        var zoomTo = NSNumber(double: 0.45)
        
        var zoomAnimation = CABasicAnimation(keyPath: "transform.scale")
        var alphaAnimation = CABasicAnimation(keyPath: "opacity")
        
        
        if(!dir) {
            zoomAnimation.fromValue =  zoomFrom
            zoomAnimation.toValue =  zoomTo
            alphaAnimation.fromValue = alphaFrom
            alphaAnimation.toValue = alphaTo
        } else {
            zoomAnimation.fromValue =  zoomTo
            zoomAnimation.toValue =  zoomFrom
            alphaAnimation.fromValue = alphaTo
            alphaAnimation.toValue = alphaFrom
        }
    
        
        alphaAnimation.duration = 0.27
        alphaAnimation.repeatCount = 1
        alphaAnimation.removedOnCompletion = false
        alphaAnimation.fillMode = kCAFillModeForwards
        alphaAnimation.timingFunction = CAMediaTimingFunction(name: kCAMediaTimingFunctionEaseInEaseOut)
        
        zoomAnimation.duration = 0.27
        zoomAnimation.repeatCount = 1
        zoomAnimation.removedOnCompletion = false
        zoomAnimation.fillMode = kCAFillModeForwards
        zoomAnimation.timingFunction = CAMediaTimingFunction(controlPoints: 0.5, 1.8, 1.0, 1.0)
        
        view.layer.addAnimation(zoomAnimation, forKey: "zoom")
        view.layer.addAnimation(alphaAnimation, forKey: "alpha")
    }
    
    func enableUserInteraction() {
        topView.userInteractionEnabled = true
        removeAllOverlays()
    }
    
    func disableUserInteraction() {
        topView.userInteractionEnabled = false
    }
    
    
    
    func putDate(formattedDate: String) {
        date = formattedDate
        buttonView.setTitle(NSDate.getDeStringDatumFromString(formattedDate), forState: UIControlState.Normal)
    }
    
    
    
    func startedConnection() {
        disableUserInteraction()
        showWaitOverlayWithText("Einen Moment...")
    }
    
    func notLoggedIn() {
        
        var alert = BPCompatibleAlertController(title: "Nicht Eingeloggt", message: "Bitte logge dich erneut ein.", alertStyle: .Alert)
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .Default, handler: nil))
        alert.presentFrom(self, animated: true, completion: nil)
        
        enableUserInteraction()
    }
    
    func unknownError() {
        
        var alert = BPCompatibleAlertController(title: "Fehler", message: "Bitte probiere es erneut", alertStyle: .Alert)
        
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .Default, handler: nil))
        alert.presentFrom(self, animated: true, completion: nil)
        
        enableUserInteraction()
    }
    
    func noInternet() {
        var alert = BPCompatibleAlertController(title: "Keine Internetverbindung", message: "Bitte probiere es später erneut.", alertStyle: .Alert)
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .Default, handler: nil))
        alert.presentFrom(self, animated: true, completion: nil)
        
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
        
        var popUp: ()-> Void = {
            if(self.previousController != nil) {
                self.previousController.dismissViewControllerAnimated(false, completion: uebersichtAktualisieren)
            } else {
                uebersichtAktualisieren()
            }
        }
        
        self.navigationController?.dismissViewControllerAnimated(false, completion: popUp)
        
    }
    
}