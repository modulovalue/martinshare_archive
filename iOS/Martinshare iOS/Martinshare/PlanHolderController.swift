
//  PlanHolderController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 27.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation
import UIKit

class PlanHolderController: UIViewController, UIScrollViewDelegate, UIWebViewDelegate {

    @IBOutlet var webView: UIWebView!
    
    @IBOutlet var topScrollView: UIScrollView!
    var pageIndex:Int!
    
    var url: String = "http://www.google.de"
    
    var onFinishedLoading: WebViewLoadProtocol!
    
    var loadedBool: Bool = false
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        webView.delegate = self
        webView.dataDetectorTypes = UIDataDetectorTypes.None
        
        self.automaticallyAdjustsScrollViewInsets = false
        
        let url = NSURL(string: self.url)
        
        webView.loadRequest(NSURLRequest(URL: NSURL(string: self.url)!))
    }
    
    
    func viewForZoomingInScrollView(scrollView: UIScrollView) -> UIView? {
        return webView
    }
    
    func webViewDidStartLoad(webView: UIWebView) {
        topScrollView.userInteractionEnabled = false
        topScrollView.zoomScale = 1
        SwiftOverlays.showCenteredWaitOverlay(topScrollView)
    }
    
    var EDITTED: Bool = false
    func webViewDidFinishLoad(webView: UIWebView) {
        
        
        topScrollView.userInteractionEnabled = true
        self.onFinishedLoading.webViewFinishedLoading()
        topScrollView.zoomScale = 1
        
        if EDITTED == false {
            
            var ursprung = webView.stringByEvaluatingJavaScriptFromString("document.documentElement.outerHTML")!
            var pre = "<span style=\"color: \(Prefs.getVertretungsplanMarkierungFarbe()); font-size: \(Prefs.getVertretungsplanMarkierungSize())pt; \">"
            var post = "</span>"
            var bedingung = Prefs.getVertretungsplanMarkierung()
            var split = ursprung.componentsSeparatedByString(bedingung)
            var ende: String = ""
            var looplength = -1 + split.count
            
            if(split.count != 0) {
                for ( var i = 0 ; i < looplength ; i++) {
                    
                    ende += split[i] + pre + bedingung + post
                }
                ende += split[split.count-1]
            } else {
                ende = ursprung
            }

            self.webView.loadHTMLString(ende, baseURL: nil)
            
            self.EDITTED = true
        }
      

        
        SwiftOverlays.removeAllOverlaysFromView(topScrollView)
    }
    
    func webView(webView: UIWebView, didFailLoadWithError error: NSError) {
        topScrollView.userInteractionEnabled = true
        self.onFinishedLoading.webViewFinishedLoadingWithError(url)
        topScrollView.zoomScale = 1
        SwiftOverlays.removeAllOverlaysFromView(topScrollView)
        
    }
    
    func webView(webView: UIWebView, shouldStartLoadWithRequest request: NSURLRequest, navigationType: UIWebViewNavigationType) -> Bool {
        if(loadedBool == false)  {
            return true
        } else {
            return false
        }
    }
}