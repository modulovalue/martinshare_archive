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
    func unknownError()
    func noInternet()
    func notChanged(warn: Bool)
    func aktualisiert(warn: Bool, afterAktualisiertOderAktuell: (()-> Void))
}