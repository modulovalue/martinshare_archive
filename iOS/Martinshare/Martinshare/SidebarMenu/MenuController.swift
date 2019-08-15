//
//  MenuController.swift
//  SidebarMenu
//
//  Created by Simon Ng on 2/2/15.
//  Copyright (c) 2015 AppCoda. All rights reserved.
//

import UIKit
import Darwin

class MenuController: UITableViewController {

    @IBOutlet var topView: UITableView!
    
    var refreshControll:UIRefreshControl!
    
    var numberOfCellsPerSection = 1;
    
    var tableArray: NSArray = NSArray()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        revealViewController().frontViewPosition.hashValue
        self.refreshControll = UIRefreshControl()
        tableView.scrollRectToVisible(CGRect(x: 0, y: 0, width: 1, height: 1), animated: false)
        self.refreshControll.attributedTitle = NSAttributedString(string: "Wird aktualisiert")
        self.refreshControll.addTarget(self, action: #selector(MenuController.refreshTable(_:)), for: UIControlEvents.valueChanged)
        self.tableView.addSubview(refreshControll)

    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
    }

    override func viewDidAppear(_ animated: Bool) {
        MartinshareAPI.getActivityArray(Prefs.getGoodUsername(), key: Prefs.getKey(), getActivity: self as GetActivityProtocol, warn: true, afterAktualisiertOderAktuell: {})
    }
    
    func refreshTable(_ sender:AnyObject) {
        MartinshareAPI.getActivityArray(Prefs.getGoodUsername(), key: Prefs.getKey(), getActivity: self as GetActivityProtocol, warn: true, afterAktualisiertOderAktuell: {})
        enableUserInteraction()
    }
    
    func enableUserInteraction() {
        //topView.userInteractionEnabled = true
        removeAllOverlays()
        tableView.reloadData()
        self.refreshControll.endRefreshing()
    }
    
    func disableUserInteraction() {
        //topView.userInteractionEnabled = false
    }

    
    // MARK: - Table view data source


    override func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return numberOfCellsPerSection
    }
    
    override func numberOfSections(in tableView: UITableView) -> Int {
        return tableArray.count
    }

   
    override func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let cell = tableView.dequeueReusableCell(withIdentifier: "reuseIdentifier", for: indexPath) as! EreignisCell

        cell.title.text         = (tableArray[(indexPath as NSIndexPath).section]["name"]! as? String)!.htmlToString
        
        let dateFormatter = DateFormatter()
        dateFormatter.dateFormat = "yyyy-MM-dd"
        print(tableArray[(indexPath as NSIndexPath).section])
        let date = dateFormatter.date(from: (tableArray[(indexPath as NSIndexPath).section]["datum"]! as? String)!)

        
        let formatter = DateFormatter()
        formatter.dateFormat = "EEEE, dd. MMM yyyy"

        cell.date.text = "\(formatter.string(from: date!))"
        cell.setSubtitleCheck((tableArray[(indexPath as NSIndexPath).section]["beschreibung"]! as? String)!)
        return cell
    }
    
    override func tableView(_ tableView: UITableView, viewForHeaderInSection section: Int) -> UIView? {
        
        let  headerCell = tableView.dequeueReusableCell(withIdentifier: "ereignisHeaderCell") as! EreignisHeaderCell
        
        
        
        headerCell.setTitle("\(getTimeString(tableArray[section]["vortimestamp"] as! String))\(tableArray[section]["toptitle"] as! String)")

        if(tableArray[section]["atype"] as! String == "show" ) {
            headerCell.firstImage.image = UIImage(named: "new")
        } else {
            headerCell.firstImage.image = nil
        }
        
        
               if(tableArray[section]["titlestyle"]! as? String == "deleted") {
            headerCell.secondImage.image = UIImage(named: "deleted")
        } else if(tableArray[section]["titlestyle"]! as? String == "new") {
            headerCell.secondImage.image = nil
        } else if(tableArray[section]["titlestyle"]! as? String == "update") {
            headerCell.secondImage.image = UIImage(named: "update")
        } else {
            headerCell.secondImage.image = nil
        }
        
        if(tableArray[section]["titletyp"]! as? String == "a") {
            headerCell.thirdImage.image = UIImage(named: "icona")
        } else if(tableArray[section]["titletyp"]! as? String == "h") {
            headerCell.thirdImage.image = UIImage(named: "iconh")
        } else if(tableArray[section]["titletyp"]! as? String == "s") {
            headerCell.thirdImage.image = UIImage(named: "icons")
        } else {
            headerCell.thirdImage.image = nil
        }
        
        return headerCell
    }

    func getTimeString(_ str: String) -> String {
        
        let timestamp = Float(str)!
        
        if(timestamp / 60 < 1) {
            return "vor \(Int(round(timestamp / 1))) \(KalenderTerminology.Sekunde(Int(round(timestamp / 1))))"
        } else if ( timestamp / 3600.0 < 1) {
            return "vor \(Int(round(timestamp / 60))) \(KalenderTerminology.Minute(Int(round(timestamp / 60))))"
        } else if ( timestamp / 86400 < 1) {
            return "vor \(Int(round(timestamp / 3600))) \(KalenderTerminology.Stunde(Int(round(timestamp / 3600))))"
        } else {
            return "vor \(Int(round(timestamp / 86400))) \(KalenderTerminology.Tag(Int(round(timestamp / 86400))))"
        }
    
    }
    
    override func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        
        if(tableArray[(indexPath as NSIndexPath).section]["atype"] as! String == "show" ) {
            
            DispatchQueue.main.async(execute: {
                self.performSegue(withIdentifier: "showDetail", sender: indexPath)
            })
            
        }
        
        self.tableView.deselectRow(at: indexPath, animated: true)
    }
    
    /*
    // MARK: - Navigation
    */
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        if(segue.identifier == "showDetail") {
            
            let controler = segue.destination as! UINavigationController
            let controller = controler.topViewController as! ShowDetailController
            let indexPath = sender as! IndexPath
            
            controller.eintrag = EintragDataContainer(nData: tableArray[(indexPath as NSIndexPath).section] as! NSDictionary)
            controller.previousController = nil
            
        }
    }


}

extension MenuController: GetActivityProtocol {
    
    func startedGetting() {
        disableUserInteraction()
        self.navigationItem.title = "Wird aktualisiert"
    }
    
    func notLoggedIn() {
        self.navigationItem.title = "Nicht Eingeloggt"
        
        enableUserInteraction()
    }
    
    
    
    func unknownError(_ string: String) {
        self.navigationItem.title = "Fehler"
        enableUserInteraction()
    }
    
    func noInternet() {
        self.navigationItem.title = "Keine Verbindung"

        enableUserInteraction()
    }
    
    func aktualisiert(_ array: NSArray, warn: Bool, afterAktualisiertOderAktuell: ()-> Void = {}) {
        enableUserInteraction()
        self.tableArray = array
        resignFirstResponder()
        
        self.navigationItem.title = "Ereignisse"
        tableView.reloadData()
        afterAktualisiertOderAktuell()
    }
    
}
