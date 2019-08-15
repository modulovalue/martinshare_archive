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
        
        NotificationCenter.default.addObserver(self, selector: #selector(EditEintragController.keyboardWillShow(_:)), name: NSNotification.Name.UIKeyboardWillShow, object: nil)
        
        NotificationCenter.default.addObserver(self, selector: #selector(EditEintragController.keyboardWillHide(_:)), name: NSNotification.Name.UIKeyboardWillHide, object: nil)
        
        
        ursprungsEintrag = eintrag.cpy()
        setTyp()
        self.beschreibungField.text = eintrag.getBeschreibung()
        
        beschreibungField.layer.borderColor = UIColor.gray.withAlphaComponent(0.2).cgColor
        beschreibungField.layer.borderWidth = 1.0
        beschreibungField.layer.cornerRadius = 6
        beschreibungField.clipsToBounds = true
        
        self.dateBtn.setTitle(Date.getDeStringDatumFromString(eintrag.datum), for: UIControlState())
        self.fachfield.text = eintrag.getTitel()
        
    }
    
    override func touchesBegan(_ touches: Set<UITouch>, with event: UIEvent?) {
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
    
    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
        resignFirstResponder()
        return true
    }
    
    func keyboardWillShow(_ sender: Notification) {
        if let userInfo = (sender as NSNotification).userInfo {
            if let keyboardHeight = (userInfo[UIKeyboardFrameEndUserInfoKey] as AnyObject).cgRectValue.size.height {
                textViewBottomConstraint.constant = keyboardHeight
                UIView.animate(withDuration: 0.25, animations: { () -> Void in
                    self.view.layoutIfNeeded()
                })
            }
        }
    }
    
    func keyboardWillHide(_ sender: Notification) {
        if let userInfo = (sender as NSNotification).userInfo {
            if let _ = (userInfo[UIKeyboardFrameEndUserInfoKey] as AnyObject).cgRectValue.size.height {
                textViewBottomConstraint.constant = 94
                UIView.animate(withDuration: 0.25, animations: { () -> Void in
                    self.view.layoutIfNeeded()
                })
            }
        }
    }
    
    @IBAction func changeDate(_ sender: AnyObject) {
        performSegue(withIdentifier: "datePicker", sender: nil)
    }
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        if(segue.identifier == "datePicker") {
            let controller = segue.destination as! UINavigationController
            let controler = controller.topViewController as! WaehleDatumController
            controler.getDate = self
            controler.altesDatum = eintrag.datum
        }
    }
    
    @IBAction func cancel(_ sender: AnyObject) {
        getNewData()
        if(!ursprungsEintrag.compare(eintrag)) {
            
        
            let alert = BPCompatibleAlertController(title: "Abbrechen", message: "Deine Änderung wird nicht gespeichert. Änderung verwerfen?", alertStyle: .alert)
            
            
            alert.addAction(BPCompatibleAlertAction(title: "Änderung verwerfen", actionStyle:
                .destructive, handler: { action in
                
                self.navigationController?.dismiss(animated: true, completion: nil)
            }))
            
            
            alert.addAction(BPCompatibleAlertAction(title: "Zurück zur Bearbeitung", actionStyle: .cancel, handler: nil))
            alert.presentFrom(self, animated: true, completion: nil)

        } else {
            navigationController?.dismiss(animated: true, completion: nil)
        }
    }
    
    @IBAction func typBtn(_ sender: AnyObject) {
        eintrag.typ = EintragDataContainer.getNextTyp(eintrag.typ)
        setTyp()
    }

    
    @IBAction func save(_ sender: AnyObject) {
        getNewData()
        MartinshareAPI.eintragUpdaten(Prefs.getGoodUsername(), key: Prefs.getKey(), newEintrag: eintrag.cpy(), eintragen: self)
    }
    
    func getNewData() {
        print(beschreibungField.text)
        eintrag.beschreibung = beschreibungField.text
        eintrag.titel = fachfield.text
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
        let alert1 = BPCompatibleAlertController(title: "Nicht Eingeloggt", message: "Bitte logge dich erneut ein.", alertStyle: .alert)
        alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.default, handler: nil))
        alert1.presentFrom(self, animated: true, completion: nil)

        enableUserInteraction()
    }
    
    func unknownError() {
        let alert1 = BPCompatibleAlertController(title: "Fehler", message: "Bitte probiere es erneut", alertStyle: .alert)
        alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.default, handler: nil))
        alert1.presentFrom(self, animated: true, completion: nil)
        
        enableUserInteraction()
    }
    
    func noInternet() {
        let alert1 = BPCompatibleAlertController(title: "Keine Internetverbindung", message: "Bitte probiere es später erneut.", alertStyle: .alert)
        alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.default, handler: nil))
        alert1.presentFrom(self, animated: true, completion: nil)
        
        enableUserInteraction()
    }
    
    func error(_ textshow: String) {
        let alert1 = BPCompatibleAlertController(title: "Martinshare meldet:", message: textshow, alertStyle: .alert)
        alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.default, handler: nil))
        alert1.presentFrom(self, animated: true, completion: nil)
        
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
        
        
        let closure: ()->Void = {
            if(self.previousController.previousController != nil) {
                self.previousController.previousController.dismiss(animated: false, completion: uebersichtAktualisieren)
            } else {
                uebersichtAktualisieren()
            }
        }
        let popUp: ()-> Void = {self.previousController.navigationController?.dismiss(animated: false, completion: closure)}
        
        self.navigationController?.dismiss(animated: false, completion: popUp)

    }
    
    func putDate(_ formattedDate: String) {
        eintrag.datum = formattedDate
        dateBtn.setTitle(Date.getDeStringDatumFromString(formattedDate), for: UIControlState())
    }
    
}


