//
//  GetActivityProtocol.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 05.02.16.
//  Copyright © 2016 ModestasV Studios. All rights reserved.
//

import Foundation

protocol GetActivityProtocol {
    
    func startedGetting()
    func notLoggedIn()
    func unknownError(_ string: String)
    func noInternet()
    func aktualisiert(_ array: NSArray, warn: Bool, afterAktualisiertOderAktuell: (()-> Void))
}
