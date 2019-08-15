//
//  GetVersionHistoryProtocol.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 24.11.15.
//  Copyright © 2015 ModestasV Studios. All rights reserved.
//

import Foundation

protocol GetVersionHistoryProtocol {
    
    func startedGetting()
    func notLoggedIn()
    func unknownError(_ string: String)
    func noInternet()
    func got(_ eintraege: Array<EintragDataContainer>)
    
}
