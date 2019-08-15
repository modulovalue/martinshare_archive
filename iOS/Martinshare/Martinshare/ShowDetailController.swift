//
//  ShowDetailController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 29.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import UIKit
import QuartzCore

class ShowDetailController: UIViewController, EintragenProtocol {

    @IBOutlet var navItem: [UINavigationItem]!
    
    var eintrag: EintragDataContainer!
    
    @IBOutlet weak var image: UIImageView!
    
    @IBOutlet weak var fach: UITextView!
    
    @IBOutlet weak var editButton: UIBarButtonItem!
    
    var previousController: UINavigationController!
    
    @IBOutlet weak var beschreibung: UITextView!
    
    @IBOutlet weak var shareButton: UIBarButtonItem!
    
    @IBOutlet weak var navBar: UINavigationItem!
    
    @IBOutlet weak var deleteButton: UIBarButtonItem!
    
    @IBOutlet weak var versionBtn: UIBarButtonItem!
    
    var isShowingVersionHistory: Bool = false
    
    @IBOutlet var topView: UIView!
    
    override func viewDidLoad() {
        
        image.image = UIImage(named: "icon\(self.eintrag.typ)pad")!
        
        if(eintrag.deleted == "1") {
            image.alpha = 0.5
            editButton.isEnabled = false
            shareButton.isEnabled = false
            deleteButton.isEnabled = false
            navBar.prompt = "Gelöscht"
        }
        
        if(eintrag.firstVersion() && !isShowingVersionHistory) {
            versionBtn.isEnabled = false
            versionBtn.tintColor = UIColor.clear
        } else {
            versionBtn.isEnabled = true
            versionBtn.tintColor = nil
        }
        
        navBar.title = Date.getDeStringDatumFromString(eintrag.datum)
        
        fach.text = eintrag.getTitel()
        beschreibung.text = eintrag.getBeschreibung()
        
        versionBtn.title = "\(versionBtn.title!) \(eintrag.version)"
        
        
        if(isShowingVersionHistory) {
            deleteButton.isEnabled = false
            shareButton.isEnabled = false
            editButton.isEnabled = false
            navBar.title = "Verlauf"
            versionBtn.isEnabled = false
            var oldPrompt = ""
            
            if(eintrag.deleted == "1") {
                oldPrompt = ", Gelöscht"
            }
            
            navBar.prompt = "Bis: \(Date.getDeStringDatumFromString(eintrag.datum))\(oldPrompt)"
        }
        
        
        fach.layer.borderColor = UIColor.gray.withAlphaComponent(0.2).cgColor
        fach.layer.borderWidth = 2.0
        fach.layer.cornerRadius = 5
        fach.clipsToBounds = true
      
        beschreibung.layer.borderColor = UIColor.gray.withAlphaComponent(0.2).cgColor
        beschreibung.layer.borderWidth = 2.0
        beschreibung.layer.cornerRadius = 5
        beschreibung.clipsToBounds = true
        
        
    }
    
    @IBAction func versionBtn(_ sender: AnyObject) {
        performSegue(withIdentifier: "showVersionHistory", sender: nil)
    }
    
    @IBAction func cancel(_ sender: AnyObject) {
        navigationController?.dismiss(animated: true, completion: nil)
    }
    
    @IBAction func edit(_ sender: AnyObject) {
        performSegue(withIdentifier: "showEdit", sender: nil)
    }
    
    @IBAction func share(_ sender: AnyObject) {
        let textToShare = "\(eintrag.typAusgeschrieben()) für den \(Date.getDeStringDatumFromString(eintrag.datum)): \n Fach: \(eintrag.getTitel()) \n Beschreibung: \(eintrag.getBeschreibung())"
        
        let objectsToShare = [textToShare]
        let activityVC = UIActivityViewController(activityItems: objectsToShare,  applicationActivities: nil)
        self.present(activityVC, animated: true, completion: nil)
        
    }
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        if(segue.identifier == "showEdit") {
            let controler = segue.destination as! UINavigationController
            let controller = controler.topViewController as! EditEintragController
                
            controller.eintrag = eintrag.cpy()
            controller.previousController = self
        } else if(segue.identifier == "showVersionHistory") {
            
            let controler = segue.destination as! UINavigationController
            let controller = controler.topViewController as! VersionController
            
            controller.eintragid = eintrag.id
            controller.previousController = self
        }
    }

    @IBAction func deleteEintragAction(_ sender: AnyObject) {
        
        let alert = BPCompatibleAlertController(title: "Wirklich löschen?", message: "Möchtest du diesen Eintrag löschen? \n Der Eintrag kann anschließend nicht mehr bearbeitet werden.", alertStyle: .alert)
        
        alert.addAction(BPCompatibleAlertAction(title: "Löschen", actionStyle:
            .destructive, handler: { action in
                
                MartinshareAPI.eintragDelete(Prefs.getGoodUsername(), key: Prefs.getKey(), deleteEintrag: self.eintrag, eintragen: self)
        }))
        
        alert.addAction(BPCompatibleAlertAction(title: "Abbrechen", actionStyle: .cancel, handler: nil))
        alert.presentFrom(self, animated: true, completion: nil)
        
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
