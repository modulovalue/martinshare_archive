//
//  KalenderController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 25.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import UIKit

class KalenderController: UIViewController, RSDFDatePickerViewDataSource, RSDFDatePickerViewDelegate {

    @IBOutlet weak var navItem: UINavigationItem!

    @IBOutlet var dateView: RSDFDatePickerView!
    
    var curDate: NSDate!
    
    static var kalenderController: KalenderController!


    override func viewDidLoad() {
        KalenderController.kalenderController = self
    }
    
    override func viewDidAppear(animated: Bool) {
        super.viewDidAppear(animated)
        self.navigationController?.navigationBar.translucent = false
        dateView.dataSource = self
        dateView.delegate = self
        dateView.reloadInputViews()
        dateView.reloadData()
    }
    
    func aktualisieren() {
        // print(NSDate().dateFromString("2015-05-25 22:0:00 +000", format: "yyyy-MM-dd HH:mm:ss ZZZ"))
        kalenderAnimieren(dateView)
        dateView.reloadData()
    }
    
    
    @IBAction func heuteBtn(sender: AnyObject) {
        dateView.selectDate(NSDate())
        dateView.scrollToToday(true)
    }

    
    override func prepareForSegue(segue: UIStoryboardSegue, sender: AnyObject?) {
        if(segue.identifier == "showOverview") {
            var controler = segue.destinationViewController as! UINavigationController
            var controller = controler.topViewController as! DayOverviewController
            
            controller.tagSetzen(curDate)
            var contarr = NSDate.eintraegeArrayFromDate(curDate, eintraege: Prefs.eintraege())

            var eintraegeTypH: Array<EintragDataContainer> = Array<EintragDataContainer>()
            var eintraegeTypA: Array<EintragDataContainer> = Array<EintragDataContainer>()
            var eintraegeTypS: Array<EintragDataContainer> = Array<EintragDataContainer>()
            
            
            for eintrag in contarr {
                if(eintrag.typ == "h") {
                    eintraegeTypH.append(eintrag)
                }
                if(eintrag.typ == "a") {
                    eintraegeTypA.append(eintrag)
                }
                if(eintrag.typ == "s") {
                    eintraegeTypS.append(eintrag)
                }
            }
            
            controller.eintraegeAmTag = contarr
            controller.eintraegeTypH = eintraegeTypH
            controller.eintraegeTypA = eintraegeTypA
            controller.eintraegeTypS = eintraegeTypS
            
        }
    }
    
    func kalenderAnimieren(calender: RSDFDatePickerView){
        
        var zoomAnimation = CABasicAnimation(keyPath: "transform.scale")
        
        zoomAnimation.fromValue =  NSNumber(double: 1)
        zoomAnimation.toValue =  NSNumber(double: 1.02)

        zoomAnimation.duration = 0.3
        zoomAnimation.repeatCount = 1
        //zoomAnimation.removedOnCompletion = false
        //zoomAnimation.fillMode = kCAFillModeForwards
        //zoomAnimation.timingFunction = CAMediaTimingFunction(name: kCAMediaTimingFunctionEaseInEaseOut)
        zoomAnimation.timingFunction = CAMediaTimingFunction(controlPoints: 0.5, 1.8, 1.0, 1.0)
        
        calender.layer.addAnimation(zoomAnimation, forKey: "zoom")
    }

    
    func datePickerView(view: RSDFDatePickerView!, isCompletedAllTasksOnDate date: NSDate!) -> Bool {
        return (date.timeIntervalSinceNow < 0)
    }
    
    func datePickerView(view: RSDFDatePickerView!, shouldHighlightDate date: NSDate!) -> Bool {
        return true
    }
    
    func datePickerView(view: RSDFDatePickerView!, shouldSelectDate date: NSDate!) -> Bool {
        curDate = date;
        performSegueWithIdentifier("showOverview", sender: nil)
        return true
    }
    
    func datePickerView(view: RSDFDatePickerView!, didSelectDate date: NSDate!) {
        
    }
    
    func datePickerView(view: RSDFDatePickerView!, shouldMarkDate date: NSDate!) -> Bool {
        let ar = Prefs.eintraegeDate()
        var anzahl = find(ar, NSDate.getStringDatum(date))
        return anzahl != nil
    }
    
}
