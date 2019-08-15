//
//  PreNotificationController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 07.02.16.
//  Copyright © 2016 ModestasV Studios. All rights reserved.
//

import Foundation

class PreNotificationController: UITableViewController {
    
    override func viewDidLoad() {
        
        
    }

    @IBAction func back(_ sender: AnyObject) {
        navigationController?.dismiss(animated: true, completion: nil)
    }
    
    override func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        
        if((indexPath as NSIndexPath).row == 0) {
            print("first row")
            performSegue(withIdentifier: "setNotifSetting", sender: "dayallnotif")
            
        } else if((indexPath as NSIndexPath).row == 1) {
            print("second row")
            performSegue(withIdentifier: "setNotifSetting", sender: "threedayanotif")
        }
        
        self.tableView.deselectRow(at: indexPath, animated: true)
    }
    
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        print("prepareForSegue")
        if segue.identifier == "setNotifSetting" {
            print("setnotifvalval \(sender as! String)")
            NotifSettingController.setNotifValVal(sender as! String)
        }
    }
}
