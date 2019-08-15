
//  VertretungsplanController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 26.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//
import UIKit

@available(iOS 8.0, *)
class VertretungsplanController: UIViewController, WebViewLoadProtocol, ConnectionProtocol {
    
    var URLS: Array<String> = Array<String>()
    

    
    var viewControllerrs: NSMutableArray = []
    
    var pageMenu: CAPSPageMenu?
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        self.navigationController?.navigationBar.isTranslucent = false
        
        
        URLS = Prefs.getVertretungsplanURL()
        
        if(URLS.count == 0) {
            MartinshareAPI.getVertretungsplan(Prefs.getGoodUsername(), key: Prefs.getKey(), conPro: self)
        } else {
            makeVertretungsplan()
        }
        
        
        
    }

    @IBAction func refresh(_ sender: AnyObject) {
        
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
    
    @IBAction func backToTop(_ sender: AnyObject) {
        navigationController?.dismiss(animated: false, completion: nil)
    }

    
    func makeVertretungsplan() {
    
        _ = self.view.subviews.map({ $0.removeFromSuperview()})
        
        URLS = Prefs.getVertretungsplanURL()
        
        var controlllerArray: [UIViewController] = []
        
        for i in 0 ..< URLS.count {
            controlllerArray.append(planHolderArIndex(i))
        }
        let parameters = [
            "menuItemSeparatorWidth": 2,
            "UseMenuLikeSegmentedControl": true,
            "MenuItemSeparatorPercentageHeight": 0.02,
            "scrollMenuBackgroundColor" : UIColor.white,
            "selectedMenuItemLabelColor": UIColor.black,
            "selectionIndicatorColor": UIColor.gray,
        ] as [String : Any]
        pageMenu = CAPSPageMenu(viewControllers: controlllerArray, frame: CGRect(x: 0.0, y: 0.0, width: self.view.frame.width, height: self.view.frame.height) as CGRect, options: parameters as [String : AnyObject]?)
        
        self.view.addSubview(pageMenu!.view)

    }
    
    
    func startedCon() {
        showWaitOverlayWithText("Wird aktualisiert...")
    }
    
    func conFailed() {
        
    }
    
    func wrongCredentials() {
        
        
        let alert = BPCompatibleAlertController(title: "Falsche Zugangsdaten", message: "Logge dich bitte erneut ein.", alertStyle: .alert)
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .cancel, handler: nil))
        
        alert.presentFrom(self, animated: true, completion: nil)
        
        removeAllOverlays()
    }
    func success() {
        
        removeAllOverlays()
        makeVertretungsplan()
    }
    func noInternetConnection() {

        let alert = BPCompatibleAlertController(title: "Keine Internetverbindung", message: "Bitte überprüfe deine Internetverbingung.", alertStyle: .alert)
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .cancel, handler: nil))
        
        alert.presentFrom(self, animated: true, completion: nil)
        
        removeAllOverlays()
        
    }
    
    func unknownError(_ string: String) {
        
    }
    
    
    
    
    @IBOutlet var topView: UIView!
    
    
    func enableUserInteraction() {
        topView.isUserInteractionEnabled = true
    }
    
    func disableUserInteraction() {
        topView.isUserInteractionEnabled = false
    }

    func planHolderArIndex(_ index: Int) -> PlanHolderController {
        let planHolderController = self.storyboard?.instantiateViewController(withIdentifier: "PlanHolderController") as! PlanHolderController
        planHolderController.url = URLS[index]
        planHolderController.pageIndex = index
        planHolderController.title = " Seite: \(1+index)"
        planHolderController.onFinishedLoading = self as WebViewLoadProtocol
        return planHolderController
    }
    
    func webViewFinishedLoading() {
        
    }
    
    func webViewFinishedLoadingWithError(_ url: String) {
    //TODO - ERROR MESSAGE
    }
    
}
