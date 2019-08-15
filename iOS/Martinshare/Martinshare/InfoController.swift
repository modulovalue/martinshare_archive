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

    @IBOutlet weak var versionLbl: UILabel!
    
    @IBAction func opendatenschutzerklärung(_ sender: AnyObject) {
        UIApplication.shared.openURL(URL(string:
            "http://www.martinshare.com/datenschutzerkl%C3%A4rung.php")!)
    }
    
    @IBAction func openHomepage(_ sender: AnyObject) {
        UIApplication.shared.openURL(URL(string: "http://www.martinshare.com/")!)
    }
    
   
    
    
    @IBAction func Lizenzen(_ sender: AnyObject) {
        
        MartinshareAPI.test()
        
        let path = Bundle.main.path(forResource: "lizenz", ofType: "txt")
        let content = try! String(contentsOfFile: path!, encoding: String.Encoding.utf8)
        
        let alert = BPCompatibleAlertController(title: "Lizenzen", message: content, alertStyle: .alert)
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .default, handler: nil))
        
        alert.presentFrom(self, animated: true, completion: nil)
        
    }
    
    @IBAction func cancel(_ sender: AnyObject) {
        navigationController?.dismiss(animated: true, completion: nil)
    }
    
    override func viewDidLoad() {
        if let version = Bundle.main.infoDictionary?["CFBundleShortVersionString"] as? String {
            versionLbl.text =  "© 2016 v\(version)"
        }
    }

}
