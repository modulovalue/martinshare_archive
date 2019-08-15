//
//  IsLoggedInProtocol.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 25.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation

protocol IsLoggedInProtocol {

    func startedChecking()
    func isLoggedIn()
    func isNotLoggedIn()
    func emptyCredentials(_ goodname: String, key:String)->Bool
    func neverWasLoggedIn()
    
}
