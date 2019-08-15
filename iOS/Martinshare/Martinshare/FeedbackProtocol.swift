//
//  FeedbackProtocol.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 07.12.15.
//  Copyright © 2015 ModestasV Studios. All rights reserved.
//

import Foundation

protocol FeedbackProtocol {
    
    func startedConnection()
    func notLoggedIn()
    func unknownError()
    func noInternetForSending()
    func sent()
    func error(_ textshow: String)
}
