//
//  VersionController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 24.11.15.
//  Copyright © 2015 ModestasV Studios. All rights reserved.
//

import Foundation
import UIKit

class VersionController: UITableViewController, GetVersionHistoryProtocol {

    @IBOutlet var topView: UITableView!
    
    var eintraege: Array<EintragDataContainer> = []
    
    var eintragid: String = ""
    
    var previousController: ShowDetailController!
    
    @IBOutlet var table: UITableView!
    
    override func viewDidLoad() {
        
        MartinshareAPI.getVersionHistory(Prefs.getGoodUsername(), key: Prefs.getKey(), id: eintragid, getVersionHistoryProtocol: self)
        
    }

    @IBAction func cancelBtn(_ sender: AnyObject) {
        navigationController?.dismiss(animated: true, completion: nil)
    }
    
    
    override func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath ) {
        DispatchQueue.main.async(execute: {
            if(self.tableView.cellForRow(at: indexPath)?.reuseIdentifier == "cell") {
                self.performSegue(withIdentifier: "showDetail", sender: indexPath)
            }
        })
    }
    
    
    override func tableView(_ tableView: UITableView, titleForHeaderInSection section: Int) -> String? {
        
        let eintrag = eintraege[section]
        
        let formatter = DateFormatter()
        formatter.dateFormat = "yyyy-MM-dd HH:mm:ss"
        let date = formatter.date(from: eintrag.erstelldatum)
        
        
        let formatter2 = DateFormatter()
        formatter2.dateFormat = "EE, dd. MMM yyyy HH:mm"
        let stringdate = formatter2.string(from: date!)
        
        return "Erstellt: \(stringdate)"
    }
    
    override func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return 1
    }
    
    
    override func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        var cell: UITableViewCell!
        
        let eintrag = eintraege[(indexPath as NSIndexPath).section]
        cell = self.tableView.dequeueReusableCell(withIdentifier: "cell")
        cell.textLabel?.text = eintrag.getTitel()
        cell.detailTextLabel?.text = eintrag.getBeschreibung()
        cell.imageView?.image = UIImage(named: "icon\(eintrag.typ)pad")

        return cell
    }
    
    override func numberOfSections(in tableView: UITableView) -> Int {
        return self.eintraege.count
    }
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
       if(segue.identifier == "showDetail") {
            
            let controler = segue.destination as! UINavigationController
            let controller = controler.topViewController as! ShowDetailController
            let indexPath = sender as! IndexPath
            
            controller.eintrag = eintraege[(indexPath as NSIndexPath).section]
            controller.isShowingVersionHistory = true
            controller.previousController = nil
            
        }
    }
    
    func enableUserInteraction() {
        topView.isUserInteractionEnabled = true
        removeAllOverlays()
        
    }
    
    func disableUserInteraction() {
        topView.isUserInteractionEnabled = false
    }
    
    
    func startedGetting() {
        
        disableUserInteraction()
        showWaitOverlayWithText("Einen Moment...")
    }
    
    func notLoggedIn() {
        
        let alert = BPCompatibleAlertController(title: "Nicht Eingeloggt", message: "Bitte logge dich erneut ein.", alertStyle: .alert)
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .default, handler: nil))
        alert.presentFrom(self, animated: true, completion: nil)
        
        enableUserInteraction()
        
    }
    
    func unknownError(_ string: String) {
        
        let alert1 = BPCompatibleAlertController(title: "Martinshare meldet:", message: string, alertStyle: .alert)
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
    
    func got(_ eintraege: Array<EintragDataContainer>) {
    
        self.eintraege = eintraege
        table.reloadData()
        enableUserInteraction()
    }
    
    

}
