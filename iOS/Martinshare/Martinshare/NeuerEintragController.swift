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

class NeuerEintragController: UIViewController, UITextFieldDelegate, GetDateProtocol, EintragenProtocol, GetSuggestion {

    @IBOutlet weak var hImage: UIImageView!
    
    @IBOutlet weak var aImage: UIImageView!

    @IBOutlet weak var sImage: UIImageView!
    
    @IBOutlet weak var fachView: AutoCompleteTextField!
    
    @IBOutlet weak var beschreibungView: UITextView!
    
    @IBOutlet weak var buttonView: UIButton!
    
    @IBOutlet var topView: UIView!
    
    @IBOutlet weak var textViewBottomConstraint: NSLayoutConstraint!
    
    var previousController: UINavigationController!
    
    var choosenTyp = ["h": false,"a":false,"s":false]
    
    var date: String!
    var origDate: String!
    
    
    var saveSug: Array<NSDictionary> = []
    
    override func viewDidLoad() {
        
        NotificationCenter.default.addObserver(self, selector: #selector(NeuerEintragController.keyboardWillShow(_:)), name: NSNotification.Name.UIKeyboardWillShow, object: nil)
        
        NotificationCenter.default.addObserver(self, selector: #selector(NeuerEintragController.keyboardWillHide(_:)), name: NSNotification.Name.UIKeyboardWillHide, object: nil)
        
        origDate = date
        
        buttonView.setTitle(Date.getDeStringDatumFromString(date), for: UIControlState())
        
        for (typ,value) in choosenTyp {
            if(value == false) {
                animateImage(getImageFromTyp(typ), dir: value)
            }
        }
        
        beschreibungView.layer.borderColor = UIColor.gray.withAlphaComponent(0.2).cgColor
        beschreibungView.layer.borderWidth = 1.0
        beschreibungView.layer.cornerRadius = 6
        beschreibungView.clipsToBounds = true
        
        fachView.autoCompleteTextColor = UIColor(red: 128.0/255.0, green: 128.0/255.0, blue: 128.0/255.0, alpha: 1.0)
        fachView.autoCompleteTextFont = UIFont(name: "HelveticaNeue-Light", size: 12.0)
        fachView.autoCompleteCellHeight = 35.0
        fachView.hidesWhenSelected = true
        fachView.maximumAutoCompleteCount = 100
        fachView.enableAttributedText = true
        fachView.hidesWhenEmpty = true
        var attributes = [String:AnyObject]()
        attributes[NSForegroundColorAttributeName] = UIColor.black
        attributes[NSFontAttributeName] = UIFont(name: "HelveticaNeue-Bold", size: 12.0)
        fachView.autoCompleteAttributes = attributes
        
        fachView.onTextChange = {[weak self] text in
            if(text.characters.count < 2) {
                
                MartinshareAPI.getNameSuggestions(Prefs.getGoodUsername(), key: Prefs.getKey(), date: self!.date, name: self!.fachView.text!, gotSuggestion: self as! GetSuggestion, fachView: self!.fachView)
                
            } else {
                self!.gotSuggestion(self!.saveSug)
            }
            
        }
        
        fachView.onSelect = {[weak self] text, indexpath in
            self!.fachView.text = text
        }
        
        
        fachView.autoCompleteTableView?.alpha = 0
    }
    
    func gotSuggestion(_ data: Array<NSDictionary>) {
        
        self.saveSug = data

        if(data.count != 0) {
            
            var sug = [String]()
            
            for value in data {
                
                if fachView.text?.characters.count == 0 {
                    sug.append(value["name"]! as! String)
                }
                
                if (value["name"]! as AnyObject).uppercased.hasPrefix(fachView.text!.uppercased()) {
                    sug.append(value["name"]! as! String)
                }
                
            }

            self.fachView.autoCompleteStrings = sug
            
            if(self.fachView.autoCompleteStrings!.count == 0) {
                print("HIDE")
                fachView.autoCompleteTableView?.alpha = 0
            } else {
                print("dont hide")
                fachView.autoCompleteTableView?.alpha = 1
            }
        }
        
    }

    
    @IBAction func fachDidBeginEditing(_ sender: AnyObject) {
        switch((fachView.text?.characters.count)! as Int) {
        case 0:
            MartinshareAPI.getNameSuggestions(Prefs.getGoodUsername(), key: Prefs.getKey(), date: date, name: "", gotSuggestion: self as GetSuggestion, fachView: fachView)
        case 1:
            MartinshareAPI.getNameSuggestions(Prefs.getGoodUsername(), key: Prefs.getKey(), date: date, name: self.fachView.text!, gotSuggestion: self as GetSuggestion, fachView: fachView)
        default:
            break
        }

    }

    @IBAction func fachDidEndEditing(_ sender: AnyObject) {
        fachView.hidesWhenEmpty = true
    }
    
    func getTypTrue()-> String {
        for (typ,value) in choosenTyp {
            if value == true {
                return typ as String
            }
        }
        return "h"
    }
    
    func setTypTrue(_ typ: String) {
        for (typ,_) in choosenTyp {
            choosenTyp[typ] = false
        }
        choosenTyp[typ] = true
    }
    
    func getImageFromTyp(_ typ: String)-> UIImageView {
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
    
    func keyboardWillShow(_ sender: Notification) {
        if let userInfo = (sender as NSNotification).userInfo {
            let keyboardHeight = (userInfo[UIKeyboardFrameEndUserInfoKey] as AnyObject).cgRectValue.size.height
            //if keyboardHeight != nil {
                textViewBottomConstraint.constant = keyboardHeight
                UIView.animate(withDuration: 0.25, animations: { () -> Void in
                    self.view.layoutIfNeeded()
                })
            //}
        }
    }
    
    func keyboardWillHide(_ sender: Notification) {
        if let userInfo = (sender as NSNotification).userInfo {
            _ = (userInfo[UIKeyboardFrameEndUserInfoKey] as AnyObject).cgRectValue.size.height
            //if keyboardHeight {
                textViewBottomConstraint.constant = 93
                UIView.animate(withDuration: 0.25, animations: { () -> Void in
                    self.view.layoutIfNeeded()
                })
           // }
        }
    }
    
    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
        beschreibungView.becomeFirstResponder()
        return false
    }
    
    @IBAction func sImageTap(_ sender: AnyObject) {
        animateImage(getImageFromTyp(getTypTrue()), dir: false)
        setTypTrue("s")
        animateImage(getImageFromTyp(getTypTrue()), dir: true)
    }

    @IBAction func aImageTap(_ sender: AnyObject) {
        animateImage(getImageFromTyp(getTypTrue()), dir: false)
        setTypTrue("a")
        animateImage(getImageFromTyp(getTypTrue()), dir: true)
    }
    
    @IBAction func hImageTap(_ sender: AnyObject) {
        animateImage(getImageFromTyp(getTypTrue()), dir: false)
        setTypTrue("h")
        animateImage(getImageFromTyp(getTypTrue()), dir: true)
    }
    
    @IBAction func dateBtn(_ sender: AnyObject) {
        performSegue(withIdentifier: "datePicker", sender: nil)
    }
    
    @IBAction func saveBtn(_ sender: AnyObject) {
        
        let warningLbl: NSMutableArray = []
        
        if(date == nil) {
            warningLbl.add("das Datum")
        }
        if(fachView.text == "") {
            warningLbl.add("das Fach")
        }
        
        if(warningLbl.count == 0) {
            MartinshareAPI.eintragEintragen(Prefs.getGoodUsername(), key: Prefs.getKey(), typ: getTypTrue(), fach: fachView.text!, beschreibung: beschreibungView.text, datum: date, eintragen: self)
        } else {
            var warn: String = ""
            
            if(warningLbl.count > 1) {
                for (index, warning) in warningLbl.enumerated() {
                    warn += warning as! String
                    if(index == 1 - (warning as AnyObject).count) {
                        warn += " "
                    } else {
                        warn += " und "
                    }
                }
            } else {
                warn = warningLbl[0] as! String
                warn += " "
            }
            
            
            let alert = BPCompatibleAlertController(title: "Bitte fülle \(warn)aus", message: "Bitte vervollständige den Eintrag.", alertStyle: .alert)
            
            alert.addAction(BPCompatibleAlertAction(title: "OK", actionStyle: .default, handler: nil))
            
            alert.presentFrom(self, animated: true, completion: nil)

        }
    }

    @IBAction func cancelBtn(_ sender: AnyObject) {
        if(beschreibungView.text != "" || fachView.text != "" || origDate != date) {
        
            

            let alert = BPCompatibleAlertController(title: "Abbrechen", message: "Deine Eingaben werden nicht gespeichert. Eingaben verwerfen?", alertStyle: .alert)
            
            
            alert.addAction(BPCompatibleAlertAction(title: "Eingaben verwerfen", actionStyle: .destructive, handler: { action in
                self.navigationController?.dismiss(animated: true, completion: nil)
            }))
            
            alert.addAction(BPCompatibleAlertAction(title: "Weiter bearbeiten", actionStyle: .cancel, handler: nil))
            
            alert.presentFrom(self, animated: true, completion: nil)
            
        } else {
            navigationController?.dismiss(animated: false, completion: nil)
        }

    }
    
    override func touchesBegan(_ touches: Set<UITouch>, with event: UIEvent?) {
        self.view.endEditing(true)
    }
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        if(segue.identifier == "datePicker") {
            let controller = segue.destination as! UINavigationController
            let controler = controller.topViewController as! WaehleDatumController
            controler.getDate = self
            controler.altesDatum = date
        }
    }

    
    func animateImage(_ view: UIImageView, dir: Bool){
        
        let alphaFrom = NSNumber(value: 1 as Double)
        let alphaTo = NSNumber(value: 0.8 as Double)
        let zoomFrom = NSNumber(value: 1 as Double)
        let zoomTo = NSNumber(value: 0.45 as Double)
        
        let zoomAnimation = CABasicAnimation(keyPath: "transform.scale")
        let alphaAnimation = CABasicAnimation(keyPath: "opacity")
        
        
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
        alphaAnimation.isRemovedOnCompletion = false
        alphaAnimation.fillMode = kCAFillModeForwards
        alphaAnimation.timingFunction = CAMediaTimingFunction(name: kCAMediaTimingFunctionEaseInEaseOut)
        
        zoomAnimation.duration = 0.27
        zoomAnimation.repeatCount = 1
        zoomAnimation.isRemovedOnCompletion = false
        zoomAnimation.fillMode = kCAFillModeForwards
        zoomAnimation.timingFunction = CAMediaTimingFunction(controlPoints: 0.5, 1.8, 1.0, 1.0)
        
        view.layer.add(zoomAnimation, forKey: "zoom")
        view.layer.add(alphaAnimation, forKey: "alpha")
    }
    
    
    func putDate(_ formattedDate: String) {
        date = formattedDate
        buttonView.setTitle(Date.getDeStringDatumFromString(formattedDate), for: UIControlState())
    }
    
    
    func enableUserInteraction() {
        topView.isUserInteractionEnabled = true
        removeAllOverlays()
    }
    
    func disableUserInteraction() {
        topView.isUserInteractionEnabled = false
    }
    
    

    
    func startedConnection() {
        disableUserInteraction()
        showWaitOverlayWithText("Einen Moment...")
    }
    
    func notLoggedIn() {
        
        let alert = BPCompatibleAlertController(title: "Nicht Eingeloggt", message: "Bitte logge dich erneut ein.", alertStyle: .alert)
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .default, handler: nil))
        alert.presentFrom(self, animated: true, completion: nil)
        
        enableUserInteraction()
    }
    
    func unknownError() {
        
        let alert = BPCompatibleAlertController(title: "Fehler", message: "Bitte probiere es erneut", alertStyle: .alert)
        
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .default, handler: nil))
        alert.presentFrom(self, animated: true, completion: nil)
        
        enableUserInteraction()
    }
    
    func noInternet() {
        let alert = BPCompatibleAlertController(title: "Keine Internetverbindung", message: "Bitte probiere es später erneut.", alertStyle: .alert)
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .default, handler: nil))
        alert.presentFrom(self, animated: true, completion: nil)
        
        enableUserInteraction()
    }
    
    func aktualisiert() {
        enableUserInteraction()
        resignFirstResponder()
        
        let kalenderAktualisieren: ()-> Void = {
            if(KalenderController.kalenderController != nil ) {
                KalenderController.kalenderController.aktualisieren(true)
            }
        }
        
        let uebersichtAktualisieren: ()-> Void = { UebersichtController.uebersichtController.aktualisiereEintraege(false, afterAktualisiertOderAktuell: kalenderAktualisieren)}
        
        let popUp: ()-> Void = {
            if(self.previousController != nil) {
                self.previousController.dismiss(animated: false, completion: uebersichtAktualisieren)
            } else {
                uebersichtAktualisieren()
            }
        }
        
        self.navigationController?.dismiss(animated: false, completion: popUp)
        
    }
    
    
    func error(_ textshow: String) {
        let alert1 = BPCompatibleAlertController(title: "Martinshare meldet:", message: textshow, alertStyle: .alert)
        alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.default, handler: nil))
        alert1.presentFrom(self, animated: true, completion: nil)
        
        enableUserInteraction()
    }
    
}
