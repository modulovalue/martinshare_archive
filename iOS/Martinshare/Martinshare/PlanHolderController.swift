
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
        webView.dataDetectorTypes = UIDataDetectorTypes()
        
        self.automaticallyAdjustsScrollViewInsets = false
        
        _ = URL(string: self.url)
        
        webView.loadRequest(URLRequest(url: URL(string: self.url)!))
    }
    
    
    func viewForZooming(in scrollView: UIScrollView) -> UIView? {
        return webView
    }
    
    func webViewDidStartLoad(_ webView: UIWebView) {
        topScrollView.isUserInteractionEnabled = false
        topScrollView.zoomScale = 1
        SwiftOverlays.showCenteredWaitOverlay(topScrollView)
    }
    
    var EDITTED: Bool = false
    func webViewDidFinishLoad(_ webView: UIWebView) {
        
        
        topScrollView.isUserInteractionEnabled = true
        self.onFinishedLoading.webViewFinishedLoading()
        topScrollView.zoomScale = 1
        
        if EDITTED == false {
            
            let ursprung = webView.stringByEvaluatingJavaScript(from: "document.documentElement.outerHTML")!
            let pre = "<span style=\"color: \(Prefs.getVertretungsplanMarkierungFarbe()); font-size: \(Prefs.getVertretungsplanMarkierungSize())pt; \">"
            let post = "</span>"
            let bedingung = Prefs.getVertretungsplanMarkierung()
            var split = ursprung.components(separatedBy: bedingung)
            var ende: String = ""
            let looplength = -1 + split.count
            
            if(split.count != 0) {
                for i in 0 ..< looplength {
                    
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
    
    func webView(_ webView: UIWebView, didFailLoadWithError error: Error) {
        topScrollView.isUserInteractionEnabled = true
        self.onFinishedLoading.webViewFinishedLoadingWithError(url)
        topScrollView.zoomScale = 1
        SwiftOverlays.removeAllOverlaysFromView(topScrollView)
        
    }
    
    func webView(_ webView: UIWebView, shouldStartLoadWith request: URLRequest, navigationType: UIWebViewNavigationType) -> Bool {
        if(loadedBool == false)  {
            return true
        } else {
            return false
        }
    }
}
