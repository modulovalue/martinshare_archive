//
//  CheckPushProtocol.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 11.10.15.
//  Copyright © 2015 ModestasV Studios. All rights reserved.
//

import Foundation

protocol CheckPushProtocol {

    func success()
    func noInternetConnection()
    func alreadyRight()
    func unknownError()
    func wrongCredentials()
    
}