//
//  UebersichtController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 29.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import UIKit

class UebersichtController: UITableViewController, GetEintraegeProtocol {

    static var uebersichtController: UebersichtController!
    
    var anzahlTage = 15
    
    @IBOutlet var topView: UIView!
    
    var sectionCountRows: [Int : Array<EintragDataContainer>] = [:]
    
    var refreshControll:UIRefreshControl!
    
    override func viewDidLoad() {
        navigationController?.navigationBar.barTintColor = UIColor.whiteColor()
        navigationController?.navigationBar.translucent = false
        navigationController?.tabBarController?.tabBar.translucent = false
        UebersichtController.uebersichtController = self
        aktualisiereEintraege(false, afterAktualisiertOderAktuell: {})
    
        var refreshControlElmt = UIRefreshControl()
        
        self.refreshControll = UIRefreshControl()
        self.refreshControll.attributedTitle = NSAttributedString(string: "")
        self.refreshControll.addTarget(self, action: "refreshTable:", forControlEvents: UIControlEvents.ValueChanged)
        self.tableView.addSubview(refreshControll)
        
        
    }
    
    func refreshTable(sender:AnyObject) {
        aktualisiereEintraege(false, afterAktualisiertOderAktuell: {})
    }
    
    func getArrayOfDaysToShow() -> Array<NSDate> {
        var datesToShow: Array<NSDate> = Array<NSDate>()
        var today = NSDate()
        var calendar = NSCalendar()
        for var i = 0; i < anzahlTage; i++ {
            
            //var date = NSCalendar.currentCalendar().dateByAddingUnit(.CalendarUnitDay, value: i, toDate: today, options: NSCalendarOptions(0))
            
            let component = NSDateComponents()
            
            component.day = i
                
            let dayday = NSCalendar.currentCalendar().dateByAddingComponents(component, toDate: today, options: NSCalendarOptions(0))
            
            datesToShow.append(dayday!)
            
            
        }
        return datesToShow
    }
    

    @IBAction func refresh(sender: AnyObject) {
        aktualisiereEintraege(true, afterAktualisiertOderAktuell: {})
    }
    
    func aktualisiereEintraege(warn: Bool, afterAktualisiertOderAktuell: (()-> Void)) {
        MartinshareAPI.getEintraege(Prefs.getGoodUsername(), key: Prefs.getKey(), lastChanged: Prefs.getEintraegeLastChanged(), geteintraege: self as GetEintraegeProtocol, warn: warn, afterAktualisiertOderAktuell: afterAktualisiertOderAktuell)
    }
    
    override func tableView(tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        
        let datumInSection = NSDate.getStringDatum(getArrayOfDaysToShow()[section])
        
        var countInArray: Int = 0
        
        var temp: Array<EintragDataContainer> = Array<EintragDataContainer>()
        
        for eintrag in Prefs.eintraege() {
            if(eintrag.datum == datumInSection) {
                countInArray++
                temp.append(eintrag)
            }
        }
        sectionCountRows[section] = temp
        
        return ++countInArray
        
    }
    
    
    override func tableView(tableView: UITableView, didSelectRowAtIndexPath indexPath: NSIndexPath ) {

        dispatch_async(dispatch_get_main_queue(), {
            if(self.tableView.cellForRowAtIndexPath(indexPath)?.reuseIdentifier == "cellAdd") {
                self.performSegueWithIdentifier("showNew", sender: indexPath)
            } else {
                self.performSegueWithIdentifier("showDetail", sender: indexPath)
            }
        })
    }
    
    override func tableView(tableView: UITableView, titleForHeaderInSection section: Int) -> String? {
        let formatter = NSDateFormatter()
        formatter.dateFormat = "EEEE, dd.MMMM.yyyy"
        return formatter.stringFromDate(getArrayOfDaysToShow()[section])
    }
    
    
    
    override func tableView(tableView: UITableView, cellForRowAtIndexPath indexPath: NSIndexPath) -> UITableViewCell {
        var cell:UITableViewCell!
        
        var celloderaddcell: Bool!
        
        if(indexPath.row >= sectionCountRows[indexPath.section]?.count) {
            cell = self.tableView.dequeueReusableCellWithIdentifier("cellAdd") as! UITableViewCell
        } else {
            var arr: Array<EintragDataContainer> = sectionCountRows[indexPath.section]!
            let eintrag = arr[indexPath.row]
            cell = self.tableView.dequeueReusableCellWithIdentifier("cell") as! UITableViewCell
            cell.textLabel?.text = eintrag.getTitel()
            cell.detailTextLabel?.text = eintrag.getBeschreibung()
            cell.imageView?.image = UIImage(named: "icon\(eintrag.typ)pad")
        }
        
        return cell
    }

    
    override func numberOfSectionsInTableView(tableView: UITableView) -> Int {
        return anzahlTage
    }
    
    override func prepareForSegue(segue: UIStoryboardSegue, sender: AnyObject?) {
        if(segue.identifier == "showDetail") {
            
            var controler = segue.destinationViewController as! UINavigationController
            var controller = controler.topViewController as! ShowDetailController
            var indexPath = sender as! NSIndexPath

            controller.eintrag = sectionCountRows[indexPath.section]![indexPath.row]
            controller.previousController = nil
            
        } else if(segue.identifier == "showNew") {
            var controler = segue.destinationViewController as! UINavigationController
            var controller = controler.topViewController as! NeuerEintragController
            var indexPath = sender as! NSIndexPath
            controller.setTypTrue("h")
            controller.date = NSDate.getStringDatum(getArrayOfDaysToShow()[indexPath.section])
            controller.previousController = nil
        }
    }
    
    
    func enableUserInteraction() {
        topView.userInteractionEnabled = true
        removeAllOverlays()
        self.refreshControll.endRefreshing()
    }
    
    func disableUserInteraction() {
        topView.userInteractionEnabled = false
    }
    
    
    func startedGetting() {
        disableUserInteraction()
        tableView.scrollRectToVisible(CGRectMake(0, 0, 1, 1), animated: false)
        showWaitOverlayWithText("Einen Moment...")
    }
    
    func notLoggedIn() {
        
        var alert = BPCompatibleAlertController(title: "Nicht Eingeloggt", message: "Bitte logge dich erneut ein.", alertStyle: .Alert)
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.Default, handler: nil))
        
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
        var alert = BPCompatibleAlertController(title: "Keine Internetverbindung", message: "Bitte Aktualisiere die Einträge wenn du eine Internetverbindung hast.", alertStyle: .Alert)
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .Default, handler: nil))
        
        alert.presentFrom(self, animated: true, completion: nil)
        
        enableUserInteraction()
    }
    
    func aktualisiert(warn: Bool, afterAktualisiertOderAktuell: ()-> Void = {}) {
        enableUserInteraction()
        resignFirstResponder()
        
        if(warn) {
            
            var alert = BPCompatibleAlertController(title: "Einträge aktualisiert", message: "Einträge wurden aktualisiert", alertStyle: .Alert)
            
            alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .Default, handler: nil))
            alert.presentFrom(self, animated: true, completion: nil)
            
        }
        tableView.reloadData()
        afterAktualisiertOderAktuell()
    }
    
    func notChanged(warn: Bool) {
        enableUserInteraction()
        resignFirstResponder()
        
        if(warn) {
            
//            var alert = BPCompatibleAlertController(title: "Nicht Eingeloggt", message: "Bitte logge dich erneut ein.", alertStyle: .Alert)
//            
//            
//            alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.Default, handler: nil))
//            alert.presentFrom(self, animated: true, completion: nil)
//            

            var alert = BPCompatibleAlertController(title: "Einträge aktuell", message: "Es gibt keine neuen Einträge", alertStyle: .Alert)
            
            alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .Default, handler: nil))
            
            alert.presentFrom(self, animated: true, completion: nil)
            
        }
        
        tableView.reloadData()
    }
    
}