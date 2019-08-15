//
//  UebersichtController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 29.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import UIKit
fileprivate func < <T : Comparable>(lhs: T?, rhs: T?) -> Bool {
  switch (lhs, rhs) {
  case let (l?, r?):
    return l < r
  case (nil, _?):
    return true
  default:
    return false
  }
}

fileprivate func > <T : Comparable>(lhs: T?, rhs: T?) -> Bool {
  switch (lhs, rhs) {
  case let (l?, r?):
    return l > r
  default:
    return rhs < lhs
  }
}


class UebersichtController: UITableViewController {

    static var uebersichtController: UebersichtController!
    
    var anzahlTage = 10
    
    var curDate: Date!
    
    @IBOutlet var topView: UIView!
    
    var sectionCountRows: [Int : Array<EintragDataContainer>] = [:]
    
    var refreshControll:UIRefreshControl!

    @IBOutlet weak var menuButton: UIBarButtonItem!
    
    override func viewDidLoad() {
        
        MSPermissions().showPermissions(false)
     
        self.navigationController?.navigationBar.barTintColor = UIColor.white
        self.navigationController?.navigationBar.isTranslucent = false
        self.navigationController?.tabBarController?.tabBar.isTranslucent = false
        UebersichtController.uebersichtController = self
        self.aktualisiereEintraege(false, afterAktualisiertOderAktuell: {})
    
        self.refreshControll = UIRefreshControl()
        self.refreshControll.attributedTitle = NSAttributedString(string: "Wird aktualisiert")
        self.refreshControll.addTarget(self, action: #selector(UebersichtController.refreshTable(_:)), for: UIControlEvents.valueChanged)
        self.tableView.addSubview(self.refreshControll)
            
    }
    
    override func viewDidAppear(_ animated: Bool) {
        if self.revealViewController() != nil {
        // revealViewController().rearViewRevealWidth = 250
        self.menuButton.target = self.revealViewController()
        self.menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
        self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
    }
    
    
    func refreshTable(_ sender:AnyObject) {
        aktualisiereEintraege(false, afterAktualisiertOderAktuell: {})
    }
    
    func getArrayOfDaysToShow() -> Array<Date> {
        var datesToShow: Array<Date> = Array<Date>()
        let today = Date()
        for i in 0 ..< anzahlTage {
            
            //var date = NSCalendar.currentCalendar().dateByAddingUnit(.CalendarUnitDay, value: i, toDate: today, options: NSCalendarOptions(0))
            
            var component = DateComponents()
            
            component.day = i
                
            let dayday = (Calendar.current as NSCalendar).date(byAdding: component, to: today, options: NSCalendar.Options(rawValue: 0))
    
            datesToShow.append(dayday!)
            
        }
        return datesToShow
    }
    

    @IBAction func refresh(_ sender: AnyObject) {
        aktualisiereEintraege(true, afterAktualisiertOderAktuell: {})
    }
    
    func aktualisiereEintraege(_ warn: Bool, afterAktualisiertOderAktuell: (()-> Void)) {
        MartinshareAPI.getEintraege(Prefs.getGoodUsername(), key: Prefs.getKey(), lastChanged: Prefs.getEintraegeLastChanged(), geteintraege: self as GetEintraegeProtocol, warn: warn, afterAktualisiertOderAktuell: afterAktualisiertOderAktuell)
    }
    
    override func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath ) {

        DispatchQueue.main.async(execute: {
            if(self.tableView.cellForRow(at: indexPath)?.reuseIdentifier == "cellAdd") {
                self.performSegue(withIdentifier: "showNew", sender: indexPath)
            } else if(self.tableView.cellForRow(at: indexPath)?.reuseIdentifier == "cell") {
                self.performSegue(withIdentifier: "showDetail", sender: indexPath)
            } else if(self.tableView.cellForRow(at: indexPath)?.reuseIdentifier == "delete") {
                self.performSegue(withIdentifier: "showOverview", sender: indexPath)
            }
        })
    }
    
    override func tableView(_ tableView: UITableView, titleForHeaderInSection section: Int) -> String? {
        let formatter = DateFormatter()
        formatter.dateFormat = "EEEE, dd. MMM yyyy"
        return formatter.string(from: getArrayOfDaysToShow()[section])
    }
    
    override func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        
        let datumInSection = Date.getStringDatum(getArrayOfDaysToShow()[section])
        
        var countInArray: Int = 0
        
        var temp: Array<EintragDataContainer> = Array<EintragDataContainer>()
        
        for eintrag in Prefs.eintraege() {
            if(eintrag.datum == datumInSection && eintrag.deleted == "0") {
                countInArray += 1
                temp.append(eintrag)
            }
        }
        sectionCountRows[section] = temp
        
        countInArray += 1
        return countInArray += 1
        
    }
    
    override func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        var cell:UITableViewCell!
        
        
        if((indexPath as NSIndexPath).row > sectionCountRows[(indexPath as NSIndexPath).section]?.count) {
            cell = self.tableView.dequeueReusableCell(withIdentifier: "delete")
            
            let datumInSection = Date.getStringDatum(getArrayOfDaysToShow()[(indexPath as NSIndexPath).section])
            
            var countDeleted = 0;
            
            for eintrag in Prefs.eintraege() {
                if(eintrag.datum == datumInSection && eintrag.deleted == "1") {
                    countDeleted += 1
                }
            }
            
            cell.textLabel?.text = ""
            cell.detailTextLabel?.text = "\(countDeleted) \(EintragDataContainer.getEintragStringSingular(countDeleted == 1))"
            cell.detailTextLabel?.alpha = 0.65
            
            if(countDeleted >= 1) {
                cell.detailTextLabel?.textColor = UIColor.red
            } else {
                cell.detailTextLabel?.textColor = UIColor.black
            }
            
            
        } else if((indexPath as NSIndexPath).row == sectionCountRows[(indexPath as NSIndexPath).section]?.count) {
            cell = self.tableView.dequeueReusableCell(withIdentifier: "cellAdd")
            
        } else  {
            var arr: Array<EintragDataContainer> = sectionCountRows[(indexPath as NSIndexPath).section]!
            let eintrag = arr[(indexPath as NSIndexPath).row]
            cell = self.tableView.dequeueReusableCell(withIdentifier: "cell")
            cell.textLabel?.text = eintrag.getTitel()
            cell.detailTextLabel?.text = eintrag.getBeschreibung()
            cell.imageView?.image = UIImage(named: "icon\(eintrag.typ)pad")
            
        }
        
        return cell
    }

    
    override func numberOfSections(in tableView: UITableView) -> Int {
        return anzahlTage
    }
    
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        if(segue.identifier == "showDetail") {
            
            let controler = segue.destination as! UINavigationController
            let controller = controler.topViewController as! ShowDetailController
            let indexPath = sender as! IndexPath

            controller.eintrag = sectionCountRows[(indexPath as NSIndexPath).section]![(indexPath as NSIndexPath).row]
            controller.previousController = nil
            
        } else if(segue.identifier == "showNew") {
            
            let controler = segue.destination as! UINavigationController
            let controller = controler.topViewController as! NeuerEintragController
            let indexPath = sender as! IndexPath
            
            controller.setTypTrue("h")
            controller.date = Date.getStringDatum(getArrayOfDaysToShow()[(indexPath as NSIndexPath).section])
            controller.previousController = nil
            
        } else if(segue.identifier == "showOverview") {
            
            let controler = segue.destination as! UINavigationController
            let controller = controler.topViewController as! DayOverviewController
            let indexPath = sender as! IndexPath
            
            controller.tagSetzen(getArrayOfDaysToShow()[(indexPath as NSIndexPath).section])
            
        }
    }
    
    func enableUserInteraction() {
        topView.isUserInteractionEnabled = true
        removeAllOverlays()
        self.refreshControll.endRefreshing()
    }
    
    func disableUserInteraction() {
        topView.isUserInteractionEnabled = false
    }
   
}

extension UebersichtController: GetEintraegeProtocol {
    
    func startedGetting() {
        disableUserInteraction()
        tableView.scrollRectToVisible(CGRect(x: 0, y: 0, width: 1, height: 1), animated: false)
        //showWaitOverlayWithText("Einen Moment...")
        self.navigationItem.title = "Wird aktualisiert"
    }
    
    func notLoggedIn() {
        
        let alert = BPCompatibleAlertController(title: "Nicht Eingeloggt", message: "Bitte logge dich erneut ein.", alertStyle: .alert)
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.default, handler: nil))
        
        //alert.presentFrom(self, animated: true, completion: nil)
        
        self.navigationItem.title = "Nicht Eingeloggt"
        enableUserInteraction()
    }
    

    
    func unknownError(_ string: String) {
        
        let alert = BPCompatibleAlertController(title: "Fehler", message: "Bitte probiere es erneut Fehler: \(string) ", alertStyle: .alert)
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .default, handler: nil))
        
        alert.presentFrom(self, animated: true, completion: nil)
        
        enableUserInteraction()
    }
    
    func noInternet() {
        let alert = BPCompatibleAlertController(title: "Keine Internetverbindung", message: "Bitte Aktualisiere die Einträge wenn du eine Internetverbindung hast.", alertStyle: .alert)
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .default, handler: nil))
        
        //alert.presentFrom(self, animated: true, completion: nil)
        
        self.navigationItem.title = "Keine Verbindung"
        enableUserInteraction()
    }
    
    func aktualisiert(_ warn: Bool, afterAktualisiertOderAktuell: ()-> Void = {}) {
        enableUserInteraction()
        resignFirstResponder()
        
        self.navigationItem.title = "Übersicht"
        if(warn) {
            
            let alert = BPCompatibleAlertController(title: "Einträge aktualisiert", message: "Einträge wurden aktualisiert", alertStyle: .alert)
            
            alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .default, handler: nil))
            alert.presentFrom(self, animated: true, completion: nil)
            
        }
        tableView.reloadData()
        
        afterAktualisiertOderAktuell()
        
        NotificationPrefContainer.registerNotifications(Prefs.getEintraegeNSArray())
    }
    
    
    func notChanged(_ warn: Bool) {
        enableUserInteraction()
        resignFirstResponder()
        
        self.navigationItem.title = "Übersicht"
        if(warn) {
            
//            var alert = BPCompatibleAlertController(title: "Nicht Eingeloggt", message: "Bitte logge dich erneut ein.", alertStyle: .Alert)
//            
//
//            alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.Default, handler: nil))
//            alert.presentFrom(self, animated: true, completion: nil)
//            
            
            let alert = BPCompatibleAlertController(title: "Einträge aktuell", message: "Es gibt keine neuen Einträge", alertStyle: .alert)
            
            alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .default, handler: nil))
            
            alert.presentFrom(self, animated: true, completion: nil)
            
        }
        
        tableView.reloadData()
        NotificationPrefContainer.registerNotifications(Prefs.getEintraegeNSArray())
    }
        
}
    
