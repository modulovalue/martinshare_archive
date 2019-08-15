//
//  ConnectionProtocol.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 26.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation

protocol ConnectionProtocol {

    func startedCon()
    func conFailed()
    func wrongCredentials()
    func success()
    func noInternetConnection()
    func unknownError(_ string: String)
}
