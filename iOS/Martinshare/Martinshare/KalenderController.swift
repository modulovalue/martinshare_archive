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
    
    var curDate: Date!
    
    static var kalenderController: KalenderController!
    
    var dateShowMarker = [String:Int]()
    
    override func viewDidLoad() {
        
        KalenderController.kalenderController = self
        
    }
    
    let sbit = 1
    let abit = 2
    let hbit = 4
    
    var calimage = 0
    
    override func viewDidAppear(_ animated: Bool) {
        super.viewDidAppear(animated)
        self.navigationController?.navigationBar.isTranslucent = false
        
        dateShowMarker = [String:Int]()
        
        self.dateView.dataSource = self
        self.dateView.delegate = self
        
        aktualisieren(false)
        
        if revealViewController() != nil {
            // revealViewController().rearViewRevealWidth = 250
            view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
    }
    
    func aktualisieren(_ animate: Bool) {
        // print(NSDate().dateFromString("2015-05-25 22:0:00 +000", format: "yyyy-MM-dd HH:mm:ss ZZZ"))
        
        let eintraege = Prefs.getEintraegeArray()
        
        let priority = DispatchQueue.GlobalQueuePriority.default
        
        DispatchQueue.global(priority: priority).async {
            
            for eintrag in eintraege {
                if self.dateShowMarker[eintrag.datum] == nil {
                    self.dateShowMarker[eintrag.datum] = 0
                }
                
                switch (eintrag.typ) {
                case "a":
                    self.dateShowMarker[eintrag.datum]! |= self.abit
                    break;
                case "h":
                    self.dateShowMarker[eintrag.datum]! |= self.hbit
                    break;
                case "s":
                    self.dateShowMarker[eintrag.datum]! |= self.sbit
                    break;
                case "f":
                    self.dateShowMarker[eintrag.datum]! |= self.sbit
                    break;
                default:
                    break;
                }
            }
            
            DispatchQueue.main.async {
                self.dateView.reloadData()
                if( animate ) {
                    self.kalenderAnimieren(self.dateView)
                }
            }
        }

        
    }
    
    
    @IBAction func heuteBtn(_ sender: AnyObject) {
        dateView.select(Date())
        dateView.scroll(toToday: true)
    }
    
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        if(segue.identifier == "showOverview") {
            let controler = segue.destination as! UINavigationController
            let controller = controler.topViewController as! DayOverviewController
            controller.tagSetzen(curDate)
        }
    }
    
    func kalenderAnimieren(_ calender: RSDFDatePickerView){
        
        let zoomAnimation = CABasicAnimation(keyPath: "transform.scale")
        
        zoomAnimation.fromValue =  NSNumber(value: 1 as Double)
        zoomAnimation.toValue =  NSNumber(value: 1.02 as Double)
        
        zoomAnimation.duration = 0.3
        zoomAnimation.repeatCount = 1
        //zoomAnimation.removedOnCompletion = false
        //zoomAnimation.fillMode = kCAFillModeForwards
        //zoomAnimation.timingFunction = CAMediaTimingFunction(name: kCAMediaTimingFunctionEaseInEaseOut)
        zoomAnimation.timingFunction = CAMediaTimingFunction(controlPoints: 0.5, 1.8, 1.0, 1.0)
        
        calender.layer.add(zoomAnimation, forKey: "zoom")
    }
    
    
    func datePickerView(_ view: RSDFDatePickerView!, isCompletedAllTasksOnDate date: Date!) -> Bool {
        return (date.timeIntervalSinceNow < 0)
    }
    
    func datePickerView(_ view: RSDFDatePickerView!, shouldHighlight date: Date!) -> Bool {
        return true
    }
    
    func datePickerView(_ view: RSDFDatePickerView!, shouldSelect date: Date!) -> Bool {
        curDate = date;
        performSegue(withIdentifier: "showOverview", sender: nil)
        return true
    }
    
    func datePickerView(_ view: RSDFDatePickerView!, didSelect date: Date!) {
        
    }
    
    func datePickerView(_ view: RSDFDatePickerView!, shouldMark date: Date!) -> Bool {
        let ar = Prefs.eintraegeDate()
        let anzahl = ar.index(of: Date.getStringDatum(date))
        return anzahl != nil
    }
    
//    func datePickerView(view: RSDFDatePickerView!, markImageColorForDate date: NSDate!) -> UIColor! {
//        
////        if(date.timeIntervalSinceNow < 0) {
//            return UIColor.greenColor()
////        } else {
////            return UIColor.lightGrayColor()
////        }
//        
//    }
    
    
    func datePickerView(_ view: RSDFDatePickerView!, markImageFor date: Date!) -> UIImage! {
        
        calimage = dateShowMarker[Date.getStringDatum(date)]!
        
        var image: UIImage
        
        if( calimage == 1) {
            image = UIImage(named: "calmarkers")!
        } else if(calimage == 2) {
            image = UIImage(named: "calmarkera")!
        } else if(calimage == 3) {
            image = UIImage(named: "calmarkeras")!
        } else if(calimage == 4) {
            image = UIImage(named: "calmarkerh")!
        } else if(calimage == 5) {
            image = UIImage(named: "calmarkerhs")!
        } else if(calimage == 6) {
            image = UIImage(named: "calmarkerha")!
        } else if(calimage == 7) {
            image = UIImage(named: "calmarkerhas")!
        } else {
            image = UIImage()
        }
        
        
        return image
        
    }
    
}
