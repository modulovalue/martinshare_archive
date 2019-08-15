//
//  DayOverviewController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 30.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation
import UIKit

class DayOverviewController: UITableViewController {
    
    @IBOutlet weak var navigationBar: UINavigationItem!
    
    var eintraegeAmTag: Array<EintragDataContainer>!
    
    
    var eintraegeTypH: Array<EintragDataContainer>       = Array<EintragDataContainer>()
    var eintraegeTypA: Array<EintragDataContainer>       = Array<EintragDataContainer>()
    var eintraegeTypS: Array<EintragDataContainer>       = Array<EintragDataContainer>()
    var eintraegeTypDeleted: Array<EintragDataContainer> = Array<EintragDataContainer>()
    
    var curEintrag: EintragDataContainer!

    var curDatum: String!
    var curDate: Date!

    var previousController: UINavigationController!
    
    override func viewDidLoad() {
        previousController = navigationController
        
        
        tableView.dataSource = self
        tableView.delegate = self
        tableView.rowHeight = 47
       
//        let refreshControl = UIRefreshControl()
//        refreshControl.attributedTitle = NSAttributedString(string: "Ziehe nach unten um zu schließen")
//        refreshControl.addTarget(self, action: Selector("close"), forControlEvents: UIControlEvents.ValueChanged)
//        refreshControl.tintColor = UIColor.clearColor()
//        self.refreshControl = refreshControl
        
    }
    
    func close() {
        navigationController?.dismiss(animated: false, completion: nil)
    }
    
    func tagSetzen(_ datum: Date){
        curDatum = Date.getStringDatum(datum)
        curDate = datum
        let dat = Date.germanDateFromString(datum)
        navigationBar.title = "\(dat)"
        
        
        let contarr = Date.eintraegeArrayFromDate(curDate, eintraege: Prefs.eintraege())
        
        
        for eintrag in contarr {
            if(eintrag.deleted == "0") {
                if(eintrag.typ == "h" ) {
                    eintraegeTypH.append(eintrag)
                }
                if(eintrag.typ == "a") {
                    eintraegeTypA.append(eintrag)
                }
                if(eintrag.typ == "s") {
                    eintraegeTypS.append(eintrag)
                }
            } else if(eintrag.deleted == "1") {
                eintraegeTypDeleted.append(eintrag)
            }
        }
        
        eintraegeAmTag = contarr

    }
    
    
    @IBAction func cancel(_ sender: AnyObject) {
        navigationController?.dismiss(animated: false, completion: nil)
    }
    
    override func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {

        switch section {
        case 0:
            return 1 + eintraegeTypH.count
        case 1:
            return 1 + eintraegeTypA.count
        case 2:
            return 1 + eintraegeTypS.count
        case 3:
            return eintraegeTypDeleted.count
        default:
            return 0
        }
    }
    
    override func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        var cell:UITableViewCell!
        var celloderaddcell: Bool!
        var eintrag: EintragDataContainer!
        
                    switch (indexPath as NSIndexPath).section{
                        
                    case 0:
                        celloderaddcell = eintraegeTypH.count > (indexPath as NSIndexPath).row && eintraegeTypH.count > 0
                        
                        if (celloderaddcell == true) {
                            cell = self.tableView.dequeueReusableCell(withIdentifier: "cell")
                            eintrag = eintraegeTypH[(indexPath as NSIndexPath).row]
                        } else {
                            cell = self.tableView.dequeueReusableCell(withIdentifier: "cellAdd")
                        }
                        
                    case 1:
                        celloderaddcell = eintraegeTypA.count > (indexPath as NSIndexPath).row && eintraegeTypA.count > 0
                        
                        if (celloderaddcell == true) {
                            cell = self.tableView.dequeueReusableCell(withIdentifier: "cell")
                            eintrag = eintraegeTypA[(indexPath as NSIndexPath).row]
                        } else {
                            cell = self.tableView.dequeueReusableCell(withIdentifier: "cellAdd")
                        }
                        
                    case 2:
                        celloderaddcell = eintraegeTypS.count > (indexPath as NSIndexPath).row && eintraegeTypS.count > 0
                        
                        if (celloderaddcell == true) {
                            cell = self.tableView.dequeueReusableCell(withIdentifier: "cell")
                            eintrag = eintraegeTypS[(indexPath as NSIndexPath).row]
                        } else {
                            cell = self.tableView.dequeueReusableCell(withIdentifier: "cellAdd")
                        }
                        
                    case 3:
                        celloderaddcell = true
                        
                        cell = self.tableView.dequeueReusableCell(withIdentifier: "cell")
                        eintrag = eintraegeTypDeleted[(indexPath as NSIndexPath).row]
                        
                    default:
                    break
                        
                }
        
        if(celloderaddcell == true) {
            cell.textLabel?.text = eintrag.getTitel()
            cell.detailTextLabel?.text = eintrag.getBeschreibung()
            cell.imageView?.image = UIImage(named: "icon\(eintrag.typ)pad")
            if(eintrag.deleted == "1") {
                cell.imageView?.alpha = 0.5
            }
        }
        
        return cell
    }
    
    fileprivate var curCell = ""
    
    override func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        
        curCell = ""
        
        switch (indexPath as NSIndexPath).section {
        case 0:
            if((indexPath as NSIndexPath).row < eintraegeTypH.count && eintraegeTypH.count > 0) {
                self.curEintrag = eintraegeTypH[(indexPath as NSIndexPath).row]
            } else {
                curCell = "h"
            }
        case 1:
            if((indexPath as NSIndexPath).row < eintraegeTypA.count && eintraegeTypA.count > 0) {
                self.curEintrag = eintraegeTypA[(indexPath as NSIndexPath).row]
            } else {
                curCell = "a"
            }
        case 2:
            if((indexPath as NSIndexPath).row < eintraegeTypS.count && eintraegeTypS.count > 0) {
                self.curEintrag = eintraegeTypS[(indexPath as NSIndexPath).row]
            } else {
                curCell = "s"
            }
        case 3:
            self.curEintrag = eintraegeTypDeleted[(indexPath as NSIndexPath).row]
                
        default:
            break
        }
        
        DispatchQueue.main.async(execute: {
            if(self.curCell != "") {
                self.performSegue(withIdentifier: "showNew", sender: indexPath)
            } else {
                self.performSegue(withIdentifier: "showDetail", sender: indexPath)
            }
        })        
    }
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        if(segue.identifier == "showDetail") {
            let controler = segue.destination as! UINavigationController
            let controller = controler.topViewController as! ShowDetailController
            controller.eintrag = curEintrag.cpy()
            controller.previousController = self.navigationController
            
        } else if(segue.identifier == "showNew") {
            let controler = segue.destination as! UINavigationController
            let controller = controler.topViewController as! NeuerEintragController
            controller.setTypTrue(curCell)
            controller.date = curDatum
            //controller.eintrag = curEintrag.cpy()
            controller.previousController = self.navigationController!
        }
    }
    
    
    override func numberOfSections(in tableView: UITableView) -> Int {
        return 4
    }
    
    override func tableView(_ tableView: UITableView, titleForHeaderInSection section: Int) -> String? {
        switch section {
        case 0:
            return "Hausaufgaben: \(eintraegeTypH.count)"
        case 1:
            return "Arbeiten/Klausuren: \(eintraegeTypA.count)"
        case 2:
            return "Sonstiges: \(eintraegeTypS.count)"
        case 3:
            return "Gelöscht: \(eintraegeTypDeleted.count)"
        default:
            return "DEFAULT"
        }
    }
}
