//
//  GetEintraegeProtocol.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 29.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation

protocol GetEintraegeProtocol {

    func startedGetting()
    func notLoggedIn()
    func unknownError(_ string: String)
    func noInternet()
    func notChanged(_ warn: Bool)
    func aktualisiert(_ warn: Bool, afterAktualisiertOderAktuell: (()-> Void))
}
