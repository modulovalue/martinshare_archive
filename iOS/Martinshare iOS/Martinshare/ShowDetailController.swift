//
//  ShowDetailController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 29.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import UIKit
import QuartzCore

class ShowDetailController: UIViewController {

    @IBOutlet var navItem: [UINavigationItem]!
    
    var eintrag: EintragDataContainer!
    
    @IBOutlet weak var image: UIImageView!
    
    @IBOutlet weak var fach: UITextView!
    
    var previousController: UINavigationController!
    
    @IBOutlet weak var beschreibung: UITextView!
    
    @IBOutlet weak var navBar: UINavigationItem!
    
    override func viewDidLoad() {
        
        image.image = UIImage(named: "icon\(self.eintrag.typ)pad")!
        navBar.title = NSDate.getDeStringDatumFromString(eintrag.datum)
        fach.text = eintrag.getTitel()
        beschreibung.text = eintrag.getBeschreibung()
        
        fach.layer.borderColor = UIColor.grayColor().colorWithAlphaComponent(0.2).CGColor
        fach.layer.borderWidth = 2.0
        fach.layer.cornerRadius = 5
        fach.clipsToBounds = true
      
        beschreibung.layer.borderColor = UIColor.grayColor().colorWithAlphaComponent(0.2).CGColor
        beschreibung.layer.borderWidth = 2.0
        beschreibung.layer.cornerRadius = 5
        beschreibung.clipsToBounds = true
    }
    
    @IBAction func cancel(sender: AnyObject) {
        navigationController?.dismissViewControllerAnimated(true, completion: nil)
    }
    
    @IBAction func edit(sender: AnyObject) {
        performSegueWithIdentifier("showEdit", sender: nil)
    }
    
    @IBAction func share(sender: AnyObject) {
        let textToShare = "\(eintrag.typAusgeschrieben()) für den \(NSDate.getDeStringDatumFromString(eintrag.datum)): \n Fach: \(eintrag.getTitel()) \n Beschreibung: \(eintrag.getBeschreibung())"
        
        let objectsToShare = [textToShare]
        let activityVC = UIActivityViewController(activityItems: objectsToShare,  applicationActivities: nil)
        self.presentViewController(activityVC, animated: true, completion: nil)
        
    }
    
    override func prepareForSegue(segue: UIStoryboardSegue, sender: AnyObject?) {
        if(segue.identifier == "showEdit") {
            var controler = segue.destinationViewController as! UINavigationController
            var controller = controler.topViewController as! EditEintragController
                
            controller.eintrag = eintrag.cpy()
            controller.previousController = self
        }
    }

    
}
