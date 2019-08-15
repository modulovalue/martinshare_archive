
//  VertretungsplanController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 26.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//
import UIKit

class VertretungsplanController: UIViewController, WebViewLoadProtocol, ConnectionProtocol {
    
    var URLS: Array<String> = Array<String>()
    
    
    var viewControllerrs: NSMutableArray = []
    
    var pageMenu: CAPSPageMenu?
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        self.navigationController?.navigationBar.translucent = false
        
        
        URLS = Prefs.getVertretungsplanURL()
        
        if(URLS.count == 0) {
            MartinshareAPI.getVertretungsplan(Prefs.getGoodUsername(), key: Prefs.getKey(), conPro: self)
        } else {
            makeVertretungsplan()
        }
        
    }

    @IBAction func refresh(sender: AnyObject) {
        print("refresh")
        
        MartinshareAPI.getVertretungsplan(Prefs.getGoodUsername(), key: Prefs.getKey(), conPro: self)
        
//        var alert = BPCompatibleAlertController(title: "pläne", message: Prefs.getVertretungsplanURL()[1], alertStyle: .Alert)
//        
//        alert.addAction(BPCompatibleAlertAction(title: "OK", actionStyle: .Default, handler: { action in
//        
//                alert.dismissViewControllerAnimated(true, completion: nil)
//        }))
//        
//        alert.presentFrom(self, animated: true,completion:nil)
        
    }
    
    
    func makeVertretungsplan() {
    
        self.view.subviews.map({ $0.removeFromSuperview()})
        
        URLS = Prefs.getVertretungsplanURL()
        
        var controlllerArray: [UIViewController] = []
        
        for (var i = 0; i < URLS.count; i++ ) {
            controlllerArray.append(planHolderArIndex(i))
        }
        var parameters = [
            "menuItemSeparatorWidth": 3,
            "UseMenuLikeSegmentedControl": true,
            "MenuItemSeparatorPercentageHeight": 0.05,
            "scrollMenuBackgroundColor" : UIColor.whiteColor(),
            "selectedMenuItemLabelColor": UIColor.blackColor(),
            "selectionIndicatorColor": UIColor.blackColor(),
        ]
        pageMenu = CAPSPageMenu(viewControllers: controlllerArray, frame: CGRectMake(0.0, 0.0, self.view.frame.width, self.view.frame.height) as CGRect, options: parameters)
        self.view.addSubview(pageMenu!.view)

    }
    
    
    func startedCon() {
        showWaitOverlayWithText("Wird aktualisiert...")
    }
    
    func conFailed() {
        
    }
    
    func wrongCredentials() {
        
        
        var alert = BPCompatibleAlertController(title: "Falsche Zugangsdaten", message: "Logge dich bitte erneut ein.", alertStyle: .Alert)
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .Cancel, handler: nil))
        
        alert.presentFrom(self, animated: true, completion: nil)
        
        removeAllOverlays()
    }
    func success() {
        
        removeAllOverlays()
        makeVertretungsplan()
    }
    func noInternetConnection() {

        var alert = BPCompatibleAlertController(title: "Keine Internetverbindung", message: "Bitte überprüfe deine Internetverbingung.", alertStyle: .Alert)
        
        alert.addAction(BPCompatibleAlertAction(title: "Mach ich", actionStyle: .Cancel, handler: nil))
        
        alert.presentFrom(self, animated: true, completion: nil)
        
        removeAllOverlays()
        
    }
    
    func unknownError() {
        
    }
    
    
    
    
    @IBOutlet var topView: UIView!
    
    
    func enableUserInteraction() {
        topView.userInteractionEnabled = true
    }
    
    func disableUserInteraction() {
        topView.userInteractionEnabled = false
    }

    func planHolderArIndex(index: Int) -> PlanHolderController {
    
        var planHolderController = self.storyboard?.instantiateViewControllerWithIdentifier("PlanHolderController") as! PlanHolderController
        planHolderController.url = URLS[index]
        planHolderController.pageIndex = index
        planHolderController.title = " Seite: \(1+index)"
        planHolderController.onFinishedLoading = self as WebViewLoadProtocol
        return planHolderController
    }
    
    func webViewFinishedLoading() {
        
    }
    
    func webViewFinishedLoadingWithError(url: String) {
    
        println("Error Loading: \(url)")
    }
    
}
