//
//  InfoController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 01.06.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation
import UIKit

class InfoController: UIViewController {

    @IBAction func opendatenschutzerklärung(sender: AnyObject) {
        UIApplication.sharedApplication().openURL(NSURL(string:
            "http://www.martinshare.com/datenschutzerkl%C3%A4rung.php")!)
    }
    
    @IBAction func openHomepage(sender: AnyObject) {
        UIApplication.sharedApplication().openURL(NSURL(string: "http://www.martinshare.com/")!)
    }
    
   
    
    
    @IBAction func Lizenzen(sender: AnyObject) {
        
        
        var path = NSBundle.mainBundle().pathForResource("lizenz", ofType: "txt")
        var content = String(contentsOfFile: path!, encoding: NSUTF8StringEncoding, error: nil)!
        
        var alert = BPCompatibleAlertController(title: "Lizenzen", message: content, alertStyle: .Alert)
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .Default, handler: nil))
        
        alert.presentFrom(self, animated: true, completion: nil)
        
    }
    
    @IBAction func cancel(sender: AnyObject) {
        navigationController?.dismissViewControllerAnimated(true, completion: nil)
    }
    override func viewDidLoad() {
        
    }

}
