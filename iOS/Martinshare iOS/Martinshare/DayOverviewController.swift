//
//  DayOverviewController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 30.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation
import UIKit

class DayOverviewController: UITableViewController, UITableViewDataSource, UITableViewDelegate {
    
    @IBOutlet weak var navigationBar: UINavigationItem!
    
    var eintraegeAmTag: Array<EintragDataContainer>!
    
    
    var eintraegeTypH: Array<EintragDataContainer> = Array<EintragDataContainer>()
    var eintraegeTypA: Array<EintragDataContainer> = Array<EintragDataContainer>()
    var eintraegeTypS: Array<EintragDataContainer> = Array<EintragDataContainer>()
    
    var curEintrag: EintragDataContainer!

    var curDatum: String!

    var previousController: UINavigationController!
    
    override func viewDidLoad() {
        tableView.dataSource = self
        tableView.delegate = self
        tableView.rowHeight = 47
        previousController = navigationController
        
    }
    
    func tagSetzen(datum: NSDate){
        curDatum = NSDate.getStringDatum(datum)
        var dat = NSDate.germanDateFromString(datum)
        navigationBar.prompt = "\(dat)"
    }
    
    
    @IBAction func cancel(sender: AnyObject) {
        navigationController?.dismissViewControllerAnimated(false, completion: nil)
    }
    
    override func tableView(tableView: UITableView, numberOfRowsInSection section: Int) -> Int {

        switch section {
        case 0:
            return 1 + eintraegeTypH.count
        case 1:
            return 1 + eintraegeTypA.count
        case 2:
            return 1 + eintraegeTypS.count
        default:
            return 0
        }
    }
    
    override func tableView(tableView: UITableView, cellForRowAtIndexPath indexPath: NSIndexPath) -> UITableViewCell {
        
        var cell:UITableViewCell!
        var celloderaddcell: Bool!
        var eintrag: EintragDataContainer!
        
                    switch indexPath.section{
                        
                    case 0:
                        celloderaddcell = eintraegeTypH.count > indexPath.row && eintraegeTypH.count > 0
                        
                        if (celloderaddcell == true) {
                            cell = self.tableView.dequeueReusableCellWithIdentifier("cell") as! UITableViewCell
                            eintrag = eintraegeTypH[indexPath.row]
                        } else {
                            cell = self.tableView.dequeueReusableCellWithIdentifier("cellAdd") as! UITableViewCell
                        }
                        
                    case 1:
                        celloderaddcell = eintraegeTypA.count > indexPath.row && eintraegeTypA.count > 0
                        
                        if (celloderaddcell == true) {
                            cell = self.tableView.dequeueReusableCellWithIdentifier("cell") as! UITableViewCell
                            eintrag = eintraegeTypA[indexPath.row]
                        } else {
                            cell = self.tableView.dequeueReusableCellWithIdentifier("cellAdd") as! UITableViewCell
                        }
                        
                    case 2:
                        celloderaddcell = eintraegeTypS.count > indexPath.row && eintraegeTypS.count > 0
                        
                        if (celloderaddcell == true) {
                            cell = self.tableView.dequeueReusableCellWithIdentifier("cell") as! UITableViewCell
                            eintrag = eintraegeTypS[indexPath.row]
                        } else {
                            cell = self.tableView.dequeueReusableCellWithIdentifier("cellAdd") as! UITableViewCell
                        }
                        
                    default:
                    break
                        
                }
        
        if(celloderaddcell == true) {
            cell.textLabel?.text = eintrag.getTitel()
            cell.detailTextLabel?.text = eintrag.getBeschreibung()
            cell.imageView?.image = UIImage(named: "icon\(eintrag.typ)pad")
        }
        
        return cell
    }
    
    private var curCell = ""
    
    override func tableView(tableView: UITableView, didSelectRowAtIndexPath indexPath: NSIndexPath) {
        
        curCell = ""
        
        switch indexPath.section {
        case 0:
            if(indexPath.row < eintraegeTypH.count && eintraegeTypH.count > 0) {
                self.curEintrag = eintraegeTypH[indexPath.row]
            } else {
                curCell = "h"
            }
        case 1:
            if(indexPath.row < eintraegeTypA.count && eintraegeTypA.count > 0) {
                self.curEintrag = eintraegeTypA[indexPath.row]
            } else {
                curCell = "a"
            }
        case 2:
            if(indexPath.row < eintraegeTypS.count && eintraegeTypS.count > 0) {
                self.curEintrag = eintraegeTypS[indexPath.row]
            } else {
                curCell = "s"
            }
        default:
            break
        }
        
        dispatch_async(dispatch_get_main_queue(), {
            if(self.curCell != "") {
                self.performSegueWithIdentifier("showNew", sender: indexPath)
            } else {
                self.performSegueWithIdentifier("showDetail", sender: indexPath)
            }
        })        
    }
    
    override func prepareForSegue(segue: UIStoryboardSegue, sender: AnyObject?) {
        if(segue.identifier == "showDetail") {
            var controler = segue.destinationViewController as! UINavigationController
            var controller = controler.topViewController as! ShowDetailController
            controller.eintrag = curEintrag.cpy()
            controller.previousController = self.navigationController
            
        } else if(segue.identifier == "showNew") {
            var controler = segue.destinationViewController as! UINavigationController
            var controller = controler.topViewController as! NeuerEintragController
            controller.setTypTrue(curCell)
            controller.date = curDatum
            //controller.eintrag = curEintrag.cpy()
            controller.previousController = self.navigationController!
        }
    }
    
    
    override func numberOfSectionsInTableView(tableView: UITableView) -> Int {
        return 3
    }
    
    override func tableView(tableView: UITableView, titleForHeaderInSection section: Int) -> String? {
        switch section {
        case 0:
            return "Hausaufgaben"
        case 1:
            return "Arbeitstermine"
        case 2:
            return "Sonstiges"
        default:
            return "DEFAULT"
        }
    }
}