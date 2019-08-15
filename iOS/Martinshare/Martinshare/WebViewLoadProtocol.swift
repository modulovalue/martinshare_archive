
//
//  WebViewLoadProtocol.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 27.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation
import UIKit

protocol WebViewLoadProtocol {

    func webViewFinishedLoading()
    func webViewFinishedLoadingWithError(_ url: String)
}
