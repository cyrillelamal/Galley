import React from 'react'
import axios from 'axios'

class Listing extends React.Component {
    constructor(props) {
        super(props);

        this.handleClick = this.handleClick.bind(this)
    }

    handleClick(e) {
        e.preventDefault()

        const action = e.target.dataset.action

        const {id} = this.props.listing

        if (action === 'read') {
            this.props.setCurrentListingId(id)
        } else if (action === 'delete') {
            axios.delete(`/api/listings/${id}`)
                .then(res => {
                    if (res.status === 200) {
                        this.props.removeListing(this.props.listing)
                    }
                })
        }
    }

    render() {
        const {name} = this.props.listing

        return (
            <a href="" className="panel-block" data-action="read" onClick={this.handleClick}>
                <span className="panel-icon">
                  <i className="fas fa-book" aria-hidden="true"/>
                </span>
                {name}
            </a>
        )
    }
}

export default Listing
