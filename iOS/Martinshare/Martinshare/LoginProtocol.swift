//
//  LoginProtocol.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 25.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation

protocol LoginProtocol {

    func startedLogingIn()
    func rightCredentials()
    func wrongCredentials()
    func noInternetConnection()
    func unknownError(_ string: String)
    
}
